<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .navbar {
            background: linear-gradient(90deg, #1a1a2e, #16213e);
            padding: 12px 20px;
        }
        .navbar-brand {
            font-size: 22px;
            font-weight: bold;
            color: #ffffff;
            transform: erase
        }
        .navbar-brand:hover {
            color: #f8b400;
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
        }
        .nav-link {
            color: #ffffff;
            margin: 0 10px;
            font-size: 18px;
            transition: all 0.3s ease-in-out;
        }
        .nav-link:hover {
            color: #f8b400;
            
        }
        .btn-custom {
            background-color: #f8b400;
            color: #1a1a2e;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background-color: #e09f3e;
            color: #fff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">FrontUp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">Usuaris</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.index') }}">Projectes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
