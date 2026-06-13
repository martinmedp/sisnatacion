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
        @if ($banners->count() > 0)
            <div id="carouselInstitucion" class="carousel slide mb-4" data-bs-ride="carousel">

                <div class="carousel-indicators">

                    @foreach ($banners as $banner)
                        <button type="button" data-bs-target="#carouselInstitucion" data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}">
                        </button>
                    @endforeach

                </div>

                <div class="carousel-inner rounded">

                    @foreach ($banners as $banner)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">

                            <img src="{{ asset($banner->imagen) }}" class="d-block w-100" alt="{{ $banner->titulo }}"
                                style="height:450px; object-fit:cover;">
                            <div class="carousel-caption d-none d-md-block">

                                @if (!empty($banner->titulo))
                                    <h2>
                                        {{ $banner->titulo }}
                                    </h2>
                                @endif

                                @if (!empty($banner->subtitulo))
                                    <p>
                                        {{ $banner->subtitulo }}
                                    </p>
                                @endif

                                @if (!empty($banner->texto_boton) && !empty($banner->url_boton))
                                    <a href="{{ $banner->url_boton }}" class="btn btn-primary">

                                        {{ $banner->texto_boton }}

                                    </a>
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselInstitucion"
                    data-bs-slide="prev">

                    <span class="carousel-control-prev-icon"></span>

                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselInstitucion"
                    data-bs-slide="next">

                    <span class="carousel-control-next-icon"></span>

                </button>

            </div>
        @endif
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
    <hr class="my-5">
    <div class="mb-4 text-center">
        <h2>
            Últimas Noticias
        </h2>
        <p class="text-muted">
            Mantente informado sobre nuestras actividades
            y novedades institucionales.
        </p>
    </div>
    <div class="row">
        @foreach ($ultimasNoticias as $noticia)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if (!empty($noticia->imagen))
                        <img src="{{ asset($noticia->imagen) }}" class="card-img-top" alt="{{ $noticia->titulo }}"
                            style="height:220px; object-fit:cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-2">
                            {{ $noticia->fecha_publicacion }}
                        </small>
                        <h5>
                            {{ $noticia->titulo }}
                        </h5>
                        <p>
                            {{ Str::limit($noticia->resumen, 100) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('noticias.show', $noticia->id) }}" class="btn btn-primary btn-sm">
                                Leer más
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('noticias') }}" class="btn btn-outline-primary">
            Ver todas las noticias
        </a>
    </div>
    <hr class="my-5">
    <div class="mb-4 text-center">
        <h2>
            Galería Institucional
        </h2>
        <p class="text-muted">
            Algunos momentos destacados de nuestra comunidad educativa.
        </p>
    </div>
    <div class="row">
        @foreach ($ultimasGalerias as $galeria)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ asset($galeria->imagen) }}" alt="{{ $galeria->titulo }}" class="card-img-top"
                        style="height:250px; object-fit:cover;">
                    <div class="card-body">
                        <h5>
                            {{ $galeria->titulo }}
                        </h5>
                        <p class="text-muted">
                            {{ $galeria->descripcion }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('galeria') }}" class="btn btn-outline-primary">
            Ver Galería Completa
        </a>
    </div>
@endsection
