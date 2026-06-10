@extends('layouts.website')

@section('content')
    <div id="carouselInstitucion" class="carousel slide mb-4" data-bs-ride="carousel">

        <div class="carousel-indicators">

            <button type="button" data-bs-target="#carouselInstitucion" data-bs-slide-to="0" class="active">
            </button>

            <button type="button" data-bs-target="#carouselInstitucion" data-bs-slide-to="1">
            </button>

            <button type="button" data-bs-target="#carouselInstitucion" data-bs-slide-to="2">
            </button>

        </div>

        <div class="carousel-inner rounded">

            <div class="carousel-item active">

                <img src="https://picsum.photos/1200/400?random=1" class="d-block w-100" alt="Imagen 1">

            </div>

            <div class="carousel-item">

                <img src="https://picsum.photos/1200/400?random=2" class="d-block w-100" alt="Imagen 2">

            </div>

            <div class="carousel-item">

                <img src="https://picsum.photos/1200/400?random=3" class="d-block w-100" alt="Imagen 3">

            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInstitucion" data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carouselInstitucion" data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

    <div class="p-5 mb-4 bg-light rounded-3">

        <div class="container-fluid py-5 text-center">

            @if (!empty($configuracion->logo))
                <img src="{{ asset($configuracion->logo) }}" alt="Logo" class="img-fluid mb-4"
                    style="max-height: 180px;">
            @endif

            <h1 class="display-5 fw-bold">
                {{ $configuracion->nombre }}
            </h1>

            <p class="fs-5 col-md-8 mx-auto">

                {{ $configuracion->descripcion }}

            </p>

            <div class="mt-4">

                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">

                    Iniciar Sesión

                </a>

                <a href="{{ route('contacto') }}" class="btn btn-outline-secondary btn-lg">

                    Contáctanos

                </a>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <h4>Misión</h4>

                    <p>
                        Próximamente podrás administrar la misión
                        institucional desde el panel administrativo.
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <h4>Visión</h4>

                    <p>
                        Próximamente podrás administrar la visión
                        institucional desde el panel administrativo.
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <h4>Información</h4>

                    <p>
                        Sistema académico y administrativo
                        para la gestión integral del colegio.
                    </p>

                </div>

            </div>

        </div>

    </div>
@endsection
