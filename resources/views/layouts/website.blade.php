<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ $configuracion->nombre ?? 'Sistema Escolar' }}
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">

                @if (!empty($configuracion->logo))
                    <img src="{{ asset($configuracion->logo) }}" alt="Logo" width="50" height="50"
                        class="me-2">
                @endif

                <span>
                    {{ $configuracion->nombre }}
                </span>

            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="menuPrincipal">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inicio') }}">
                            Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('nosotros') }}">
                            Nosotros
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admisiones') }}">
                            Admisiones
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('noticias') }}">
                            Noticias
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('galeria') }}">
                            Galería
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacto') }}">
                            Contacto
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-light ms-2" href="{{ route('login') }}">
                            Ingresar
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <!-- Contenido -->
    <main class="container mt-4">

        @yield('content')

    </main>

    <!-- Footer -->
    <footer class="bg-light mt-5 py-4 border-top">

        <div class="container text-center">

            <h5>{{ $configuracion->nombre }}</h5>

            <p class="mb-1">
                {{ $configuracion->direccion }}
            </p>

            <p class="mb-1">
                {{ $configuracion->correo_electronico }}
            </p>

            <p class="mb-0">
                {{ $configuracion->telefono1 }}
            </p>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
