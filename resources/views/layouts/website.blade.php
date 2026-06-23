<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ $configuracion->nombre ?? 'Sistema Escolar' }}
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('../resources/css/inicio.css') }}">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" data-bs-theme="light">
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
                        <a class="nav-link" href="{{ route('docentes') }}">
                            Docentes
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
    <!-- Banner -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>
                        {{ $configuracion->nombre }}
                    </h5>
                    <p>
                        {{ $configuracion->descripcion }}
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>
                        Contacto
                    </h5>
                    <p class="mb-1">
                        {{ $configuracion->direccion }}
                    </p>
                    <p class="mb-1">
                        {{ $configuracion->telefono1 }}
                    </p>
                    <p class="mb-1">
                        {{ $configuracion->correo_electronico }}
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>
                        Enlaces
                    </h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('inicio') }}" class="text-white text-decoration-none">
                                Inicio
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('nosotros') }}" class="text-white text-decoration-none">
                                Nosotros
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('noticias') }}" class="text-white text-decoration-none">
                                Noticias
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('galeria') }}" class="text-white text-decoration-none">
                                Galería
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('docentes') }}">
                                Docentes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contacto') }}" class="text-white text-decoration-none">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small>
                    © {{ date('Y') }} EXENGINER. Todos los derechos reservados.
                    {{-- {{ $configuracion->nombre }} --}}
                </small>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
