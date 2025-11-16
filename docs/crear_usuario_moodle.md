1. Endpoint REST
POST https://TU-MOODLE/webservice/rest/server.php?wstoken=TU_TOKEN&moodlewsrestformat=json

2. Función a llamar
wsfunction=core_user_create_users

3. Body (x-www-form-urlencoded)

## Este es el payload mínimo necesario:

users[0][username]=correo_institucional@tu_dominio
users[0][email]=correo_institucional@tu_dominio
users[0][firstname]=NOMBRE
users[0][lastname]=APELLIDO
users[0][auth]=manual

## Nota

Si tu Moodle usa OpenID Connect (Office365), entonces "auth"="oidc"
(depende de cómo esté configurado tu plugin de autenticación).

manual = deja al plugin de autenticación tomar el control cuando el usuario intenta loguearse

oidc = crea el usuario explícitamente para usar el plugin Office 365


EJEMPLO COMPLETO (curl)
curl -X POST "https://campus.utn.edu/webservice/rest/server.php" \
     -d "wstoken=ABCD1234XYZ" \
     -d "wsfunction=core_user_create_users" \
     -d "moodlewsrestformat=json" \
     -d "users[0][username]=juan.perez@alumnos.uti.edu" \
     -d "users[0][email]=juan.perez@alumnos.uti.edu" \
     -d "users[0][firstname]=Juan" \
     -d "users[0][lastname]=Pérez" \
     -d "users[0][auth]=oidc"


Respuesta típica:

[
  { "id": 1234 }
]

REQUISITOS ANTES DE LLAMAR A LA API

- Habilitar Web Services en Moodle

- Habilitar protocolos REST

- Crear un Token para un usuario con rol "manager" o similar

- Habilitar la función core_user_create_users en el servicio

## ¿Qué hace Moodle con este usuario?

Con auth = oidc:

El usuario existe en Moodle con email institucional.

Cuando va a iniciar sesión → Moodle redirige a Office365.

El plugin OIDC valida y entra sin password local.

No hay que guardar claves en Moodle.


# ESto en la clase que habria que utilizar

Datos:
Constantes (en .env)

MOODLE_BASE_URL=https://campus.tu-dominio.edu
MOODLE_TOKEN=TU_TOKEN_DE_MOODLE
MOODLE_AUTH_METHOD=oidc


### app\Services\MoodleCliente

```
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

    /**
     * Crea un usuario en Moodle.
     *
     * Devuelve el ID del usuario creado.
     */
    public function createUser(string $email, string $firstName, string $lastName): int
    {
        $endpoint = $this->baseUrl . '/webservice/rest/server.php';

        $payload = [
            'wstoken'              => $this->token,
            'wsfunction'           => 'core_user_create_users',
            'moodlewsrestformat'   => 'json',

            // users[0][campo]...
            'users[0][username]'   => $email,
            'users[0][email]'      => $email,
            'users[0][firstname]'  => $firstName,
            'users[0][lastname]'   => $lastName,
            'users[0][auth]'       => $this->authMethod,
        ];

        $response = Http::asForm()->post($endpoint, $payload);

        if ($response->failed()) {
            throw new RuntimeException('Error HTTP al crear usuario en Moodle: ' . $response->body());
        }

        $data = $response->json();

        // Si hay error, Moodle responde con un objeto "exception"
        if (isset($data['exception'])) {
            throw new RuntimeException(
                'Error Moodle al crear usuario: ' .
                ($data['message'] ?? 'Unknown') .
                ' (' . ($data['errorcode'] ?? 'no_errorcode') . ')'
            );
        }

        // Respuesta típica: [ { "id": 1234 } ]
        if (!is_array($data) || empty($data[0]['id'])) {
            throw new RuntimeException('Respuesta inesperada de Moodle al crear usuario: ' . json_encode($data));
        }

        return (int) $data[0]['id'];
    }

    /**
     * Busca un usuario por email.
     *
     * Devuelve el array de Moodle o null si no existe.
     */
    public function findUserByEmail(string $email): ?array
    {
        $endpoint = $this->baseUrl . '/webservice/rest/server.php';

        $payload = [
            'wstoken'              => $this->token,
            'wsfunction'           => 'core_user_get_users_by_field',
            'moodlewsrestformat'   => 'json',

            'field'                => 'email',
            'values[0]'            => $email,
        ];

        $response = Http::asForm()->post($endpoint, $payload);

        if ($response->failed()) {
            throw new RuntimeException('Error HTTP al buscar usuario en Moodle: ' . $response->body());
        }

        $data = $response->json();

        if (isset($data['exception'])) {
            throw new RuntimeException(
                'Error Moodle al buscar usuario: ' .
                ($data['message'] ?? 'Unknown') .
                ' (' . ($data['errorcode'] ?? 'no_errorcode') . ')'
            );
        }

        // Respuesta típica: [] o [ { usuario... } ]
        if (empty($data) || !is_array($data)) {
            return null;
        }

        return $data[0] ?? null;
    }

    /**
     * Asegura que el usuario exista en Moodle.
     *
     * - Si existe (por email): devuelve su id.
     * - Si no existe: lo crea y devuelve id.
     */
    public function ensureUser(string $email, string $firstName, string $lastName): int
    {
        $user = $this->findUserByEmail($email);

        if ($user && !empty($user['id'])) {
            return (int) $user['id'];
        }

        return $this->createUser($email, $firstName, $lastName);
    }
}
```

Carlos