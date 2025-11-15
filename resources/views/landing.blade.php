{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lucy</title>
</head>
<body style="font-family: system-ui; display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:100vh;">
    <h1 style="font-size: 2rem; margin-bottom: 1rem;">
        Bienvenido a Lucy
    </h1>

    <p style="margin-bottom: 1rem;">
        Para acceder al sistema, iniciá sesión.
    </p>

    <a href="{{ route('login') }}"
       style="padding: 0.5rem 1rem; background:#2563eb; color:white; border-radius:0.375rem; text-decoration:none;">
        Ir al login
    </a>
</body>
</html>

