<?php

namespace App\Filament\Resources\Alumnos\Pages;

use App\Filament\Resources\Alumnos\AlumnoResource;
use App\Models\Alumno;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EditAlumno extends EditRecord
{
    protected static string $resource = AlumnoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Actions\Action::make('crearCuentaTeams')
                ->label('Crear cuenta Teams')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function (Alumno $record) {
                    $tenantId = env('GRAPH_TENANT_ID');
                    $clientId = env('GRAPH_CLIENT_ID');
                    $clientSecret = env('GRAPH_CLIENT_SECRET');
                    $domain = env('GRAPH_DEFAULT_DOMAIN');
                    $usageLocation = env('GRAPH_DEFAULT_USAGE_LOCATION', 'AR');

                    if (! $tenantId || ! $clientId || ! $clientSecret || ! $domain) {
                        Notification::make()
                            ->danger()
                            ->title('Falta configuración')
                            ->body('Configura GRAPH_TENANT_ID, GRAPH_CLIENT_ID, GRAPH_CLIENT_SECRET y GRAPH_DEFAULT_DOMAIN en .env')
                            ->send();

                        return;
                    }

                    $tokenResponse = Http::asForm()->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'scope' => 'https://graph.microsoft.com/.default',
                        'grant_type' => 'client_credentials',
                    ]);

                    if (! $tokenResponse->successful()) {
                        Notification::make()
                            ->danger()
                            ->title('No se pudo obtener token Graph')
                            ->body($tokenResponse->body() ?: 'Revisar credenciales.')
                            ->send();
                        return;
                    }

                    $accessToken = $tokenResponse->json('access_token');

                    $password = $record->teams_password ?: Str::password();
                    $email = $record->email_institucional ?: 'a'.$record->dni.'@'.$domain;

                    $payload = [
                        'accountEnabled' => true,
                        'displayName' => trim($record->nombre.' '.$record->apellido),
                        'givenName' => $record->nombre,
                        'surname' => $record->apellido,
                        'mailNickname' => 'a'.$record->dni,
                        'userPrincipalName' => $email,
                        'passwordProfile' => [
                            'forceChangePasswordNextSignIn' => true,
                            'password' => $password,
                        ],
                        'mobilePhone' => $record->telefono,
                        'usageLocation' => $usageLocation,
                    ];

                    $response = Http::withToken($accessToken)
                        ->post('https://graph.microsoft.com/v1.0/users', $payload);

                    $record->teams_payload = json_encode($payload);

                    if ($response->successful()) {
                        $record->email_institucional = $email;
                        $record->teams_password = $password;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Cuenta Teams creada')
                            ->body("Usuario: {$email}")
                            ->send();
                    } else {
                        $record->save();

                        Notification::make()
                            ->danger()
                            ->title('Error al crear cuenta Teams')
                            ->body($response->body() ?: 'Revisar configuración o payload.')
                            ->send();
                    }
                }),
        ];
    }
}
