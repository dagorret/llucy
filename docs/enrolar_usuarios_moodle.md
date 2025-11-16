# Enrolar(UFA:Matricular) Usuarios en Moodle desde Lucy

## APIs de Moodle que vas a usar

### A) Matricular en un curso

Función: `enrol_manual_enrol_users`

- Endpoint:
  
  `POST {BASE_URL}/webservice/rest/server.php`

- Parámetros principales:
  
  `wstoken=TU_TOKEN wsfunction=enrol_manual_enrol_users moodlewsrestformat=json users[0][userid]=ID_USUARIO_MOODLE users[0][courseid]=ID_CURSO_MOODLE users[0][roleid]=5`

> `roleid = 5` es el rol “student” por defecto en Moodle (alumno).

---

### B) Agregar al grupo (comisión)

Función: `core_group_add_group_members`

- Endpoint: el mismo

- Parámetros:
  
  `wstoken=TU_TOKEN wsfunction=core_group_add_group_members moodlewsrestformat=json members[0][groupid]=ID_GRUPO_MOODLE members[0][userid]=ID_USUARIO_MOODLE`

> Como ya tenés la tabla de equivalencia  
> `ID_CATEDRA -> ID_CURSO_MOODLE`  
> `COMISION_MATRICULACION -> ID_GRUPO_MOODLE`  
> lo ideal es **guardar directamente los IDs numéricos de Moodle** en esa tabla para no depender de nombres.

## 1) Configuración Laravel: `config/moodle.php` + `.env`

```php
<?php

return [
    'base_url' => env('MOODLE_BASE_URL', ''),
    'token' => env('MOODLE_TOKEN', ''),
    'auth_method' => env('MOODLE_AUTH_METHOD', 'oidc'),
];
```

`.env`:

```env
MOODLE_BASE_URL=https://campus.tu-dominio.edu
MOODLE_TOKEN=TU_TOKEN_MOODLE
MOODLE_AUTH_METHOD=oidc
```

## 2) App\Services\MoodleClient

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MoodleClient
{
    protected string $baseUrl;
    protected string $token;
    protected string $authMethod;

    public function __construct()
    {
        $this->baseUrl    = rtrim(config('moodle.base_url'), '/');
        $this->token      = config('moodle.token');
        $this->authMethod = config('moodle.auth_method', 'oidc');

        if (empty($this->baseUrl) || empty($this->token)) {
            throw new RuntimeException('MoodleClient no está configurado. Revisar config/moodle.php y .env');
        }
    }

    protected function call(string $function, array $params = [])
    {
        $endpoint = $this->baseUrl . '/webservice/rest/server.php';

        $payload = array_merge([
            'wstoken'            => $this->token,
            'wsfunction'         => $function,
            'moodlewsrestformat' => 'json',
        ], $params);

        $response = Http::asForm()->post($endpoint, $payload);

        if ($response->failed()) {
            throw new RuntimeException("Error HTTP al llamar a Moodle ({$function}): " . $response->body());
        }

        $data = $response->json();

        if (isset($data['exception'])) {
            throw new RuntimeException(
                "Error Moodle en {$function}: " .
                ($data['message'] ?? 'Unknown') .
                ' (' . ($data['errorcode'] ?? 'no_errorcode') . ')'
            );
        }

        return $data;
    }

    public function createUser(string $email, string $firstName, string $lastName): int
    {
        $data = $this->call('core_user_create_users', [
            'users[0][username]'  => $email,
            'users[0][email]'     => $email,
            'users[0][firstname]' => $firstName,
            'users[0][lastname]'  => $lastName,
            'users[0][auth]'      => $this->authMethod,
        ]);

        if (!is_array($data) || empty($data[0]['id'])) {
            throw new RuntimeException('Respuesta inesperada de core_user_create_users: ' . json_encode($data));
        }

        return (int) $data[0]['id'];
    }

    public function findUserByEmail(string $email): ?array
    {
        $data = $this->call('core_user_get_users_by_field', [
            'field'     => 'email',
            'values[0]' => $email,
        ]);

        if (empty($data) || !is_array($data)) {
            return null;
        }

        return $data[0] ?? null;
    }

    public function ensureUser(string $email, string $firstName, string $lastName): int
    {
        $user = $this->findUserByEmail($email);

        if ($user && !empty($user['id'])) {
            return (int) $user['id'];
        }

        return $this->createUser($email, $firstName, $lastName);
    }

    public function enrolUserToCourse(int $userId, int $courseId, int $roleId = 5): void
    {
        $this->call('enrol_manual_enrol_users', [
            'users[0][userid]'   => $userId,
            'users[0][courseid]' => $courseId,
            'users[0][roleid]'   => $roleId,
        ]);
    }

    public function addUserToGroup(int $userId, int $groupId): void
    {
        $this->call('core_group_add_group_members', [
            'members[0][groupid]' => $groupId,
            'members[0][userid]'  => $userId,
        ]);
    }

    public function ensureUserEnrolledInCourseAndGroup(
        string $email,
        string $firstName,
        string $lastName,
        int $courseId,
        ?int $groupId = null,
        int $roleId = 5
    ): int {
        $userId = $this->ensureUser($email, $firstName, $lastName);
        $this->enrolUserToCourse($userId, $courseId, $roleId);

        if ($groupId !== null) {
            $this->addUserToGroup($userId, $groupId);
        }

        return $userId;
    }
}
```

## 3) Uso desde Lucy

```php
use App\Services\MoodleClient;
use App\Models\Alumno;
use App\Models\Comision;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatricularAlumnoEnMoodleJob implements ShouldQueue
{
    public function __construct(
        protected int $alumnoId,
        protected int $comisionId
    ) {}

    public function handle(MoodleClient $moodle)
    {
        $alumno   = Alumno::findOrFail($this->alumnoId);
        $comision = Comision::with('catedra')->findOrFail($this->comisionId);

        $email     = $alumno->email_institucional;
        $firstName = $alumno->nombre;
        $lastName  = $alumno->apellido;

        $courseId  = $comision->catedra->moodle_course_id;
        $groupId   = $comision->moodle_group_id;

        $moodleUserId = $moodle->ensureUserEnrolledInCourseAndGroup(
            $email,
            $firstName,
            $lastName,
            $courseId,
            $groupId,
            5
        );

        $alumno->moodle_user_id = $moodleUserId;
        $alumno->save();
    }
}
```
