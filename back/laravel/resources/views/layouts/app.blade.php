<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-light" href="{{ route('users.index') }}">Usuarios</a>
    </nav>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
