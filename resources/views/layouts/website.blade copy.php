<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">

    <title>
        {{ $configuracion->nombre ?? 'Sistema Escolar' }}
    </title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            background: #0d6efd;
            color: white;
            padding: 20px;
        }

        nav {
            margin-top: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        main {
            padding: 20px;
        }

        footer {
            background: #f5f5f5;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        .logo {
            max-height: 80px;
        }
    </style>

</head>

<body>

    <header>

        @if (!empty($configuracion->logo))
        <img src="{{ asset($configuracion->logo) }}" alt="Logo" class="logo">
        @endif

        <h1>{{ $configuracion->nombre }}</h1>

        <nav>

            <a href="{{ route('inicio') }}">
                Inicio
            </a>

            <a href="{{ route('nosotros') }}">
                Nosotros
            </a>

            <a href="{{ route('admisiones') }}">
                Admisiones
            </a>

            <a href="{{ route('noticias') }}">
                Noticias
            </a>

            <a href="{{ route('galeria') }}">
                Galería
            </a>

            <a href="{{ route('contacto') }}">
                Contacto
            </a>

            <a href="{{ route('login') }}">
                Login
            </a>

        </nav>

    </header>

    <main>

        @yield('content')

    </main>

    <footer>

        <p>{{ $configuracion->direccion }}</p>

        <p>{{ $configuracion->correo_electronico }}</p>

    </footer>

</body>

</html>