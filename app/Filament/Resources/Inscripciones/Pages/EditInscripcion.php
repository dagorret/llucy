<?php

namespace App\Filament\Resources\Inscripciones\Pages;

use App\Filament\Resources\Inscripciones\InscripcionResource;
use App\Models\Inscripcion;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EditInscripcion extends EditRecord
{
    protected static string $resource = InscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Actions\Action::make('enviarMoodle')
                ->label('Enviar a Moodle')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function (Inscripcion $record) {
                    // MOODLE-TODO: Las llamadas HTTP reales están comentadas. Solo se generan payloads.

                    $token = env('MOODLE_TOKEN');
                    $domain = env('MOODLE_DOMAIN', 'https://moodle.edu.ar');

                    if (! $token || ! $domain) {
                        Notification::make()
                            ->danger()
                            ->title('Falta configuración')
                            ->body('Configura MOODLE_TOKEN y MOODLE_DOMAIN en .env')
                            ->send();
                        return;
                    }

                    $alumno = $record->alumno;
                    $catedra = $record->catedra;

                    if (! $alumno || ! $catedra) {
                        Notification::make()
                            ->danger()
                            ->title('Faltan datos')
                            ->body('La inscripción debe tener alumno y cátedra.')
                            ->send();
                        return;
                    }

                    $email = $alumno->email_institucional ?: ('a'.$alumno->dni.'@'.(parse_url($domain, PHP_URL_HOST) ?: 'moodle.edu.ar'));
                    $password = $alumno->teams_password ?: Str::password();
                    $username = $email;

                    $logs = [];
                    $baseUrl = rtrim($domain, '/');

                    // 1) Buscar usuario en Moodle por email
                    $findUserParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_user_get_users_by_field',
                        'field' => 'email',
                        'values[0]' => $email,
                        'moodlewsrestformat' => 'json',
                    ];
                    $logs[] = '# Buscar usuario: '.json_encode($findUserParams);
                    $logs[] = '# curl (MOODLE-TODO) GET '.$baseUrl.'/webservice/rest/server.php?'.http_build_query($findUserParams);
                    // MOODLE-TODO: Http::asForm()->get("{$baseUrl}/webservice/rest/server.php", $findUserParams);

                    // 2) Crear usuario si no existe
                    $createUserParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_user_create_users',
                        'moodlewsrestformat' => 'json',
                        'users[0][username]' => $username,
                        'users[0][password]' => $password,
                        'users[0][firstname]' => $alumno->nombre,
                        'users[0][lastname]' => $alumno->apellido,
                        'users[0][email]' => $email,
                        'users[0][auth]' => 'oidc',
                        'users[0][country]' => env('GRAPH_DEFAULT_USAGE_LOCATION', 'AR'),
                    ];
                    $logs[] = '# Crear usuario: '.json_encode($createUserParams);
                    $logs[] = '# curl (MOODLE-TODO) -X POST '.$baseUrl.'/webservice/rest/server.php -d "'.http_build_query($createUserParams).'"';
                    // MOODLE-TODO: Http::asForm()->post("{$baseUrl}/webservice/rest/server.php", $createUserParams);

                    // 3) Obtener curso por shortname = código de cátedra
                    $courseParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_course_get_courses_by_field',
                        'field' => 'shortname',
                        'value' => $catedra->codigo,
                        'moodlewsrestformat' => 'json',
                    ];
                    $logs[] = '# Buscar curso: '.json_encode($courseParams);
                    $logs[] = '# curl (MOODLE-TODO) GET '.$baseUrl.'/webservice/rest/server.php?'.http_build_query($courseParams);
                    // MOODLE-TODO: Http::asForm()->get("{$baseUrl}/webservice/rest/server.php", $courseParams);

                    // 4) Enrolar usuario en curso (roleid 5 = estudiante)
                    $enrolParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'enrol_manual_enrol_users',
                        'moodlewsrestformat' => 'json',
                        'enrolments[0][roleid]' => 5,
                        'enrolments[0][userid]' => '{USER_ID}',
                        'enrolments[0][courseid]' => '{COURSE_ID}',
                    ];
                    $logs[] = '# Enrolar usuario: '.json_encode($enrolParams);
                    $logs[] = '# curl (MOODLE-TODO) -X POST '.$baseUrl.'/webservice/rest/server.php -d "'.http_build_query($enrolParams).'"';
                    // MOODLE-TODO: Http::asForm()->post("{$baseUrl}/webservice/rest/server.php", $enrolParams);

                    // 5) Crear/obtener grupo según modalidad (P/D/A)
                    $grupoNombre = match ($catedra->modalidad) {
                        'presencial' => 'P',
                        'distancia' => 'D',
                        default => 'A',
                    };

                    $groupsParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_group_get_course_groups',
                        'moodlewsrestformat' => 'json',
                        'courseid' => '{COURSE_ID}',
                    ];
                    $logs[] = '# Listar grupos: '.json_encode($groupsParams);
                    $logs[] = '# curl (MOODLE-TODO) GET '.$baseUrl.'/webservice/rest/server.php?'.http_build_query($groupsParams);
                    // MOODLE-TODO: Http::asForm()->get("{$baseUrl}/webservice/rest/server.php", $groupsParams);

                    $createGroupParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_group_create_groups',
                        'moodlewsrestformat' => 'json',
                        'groups[0][courseid]' => '{COURSE_ID}',
                        'groups[0][name]' => $grupoNombre,
                    ];
                    $logs[] = '# Crear grupo: '.json_encode($createGroupParams);
                    $logs[] = '# curl (MOODLE-TODO) -X POST '.$baseUrl.'/webservice/rest/server.php -d "'.http_build_query($createGroupParams).'"';
                    // MOODLE-TODO: Http::asForm()->post("{$baseUrl}/webservice/rest/server.php", $createGroupParams);

                    $addMemberParams = [
                        'wstoken' => $token,
                        'wsfunction' => 'core_group_add_group_members',
                        'moodlewsrestformat' => 'json',
                        'members[0][groupid]' => '{GROUP_ID}',
                        'members[0][userid]' => '{USER_ID}',
                    ];
                    $logs[] = '# Añadir a grupo: '.json_encode($addMemberParams);
                    $logs[] = '# curl (MOODLE-TODO) -X POST '.$baseUrl.'/webservice/rest/server.php -d "'.http_build_query($addMemberParams).'"';
                    // MOODLE-TODO: Http::asForm()->post("{$baseUrl}/webservice/rest/server.php", $addMemberParams);

                    $record->moodle_payload = collect($logs)->join("\n");
                    $record->save();

                    Notification::make()
                        ->success()
                        ->title('Payload Moodle preparado')
                        ->body('Se generaron los payloads, las llamadas HTTP están comentadas (MOODLE-TODO).')
                        ->send();
                }),
        ];
    }
}
