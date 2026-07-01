@extends('layouts.website')

@section('content')
    {{-- ── Carrusel ── --}}
    @if ($banners->count() > 0)
        <div id="carouselInstitucion" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-indicators">
                @foreach ($banners as $banner)
                    <button type="button" data-bs-target="#carouselInstitucion" data-bs-slide-to="{{ $loop->index }}"
                        class="{{ $loop->first ? 'active' : '' }}">
                    </button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach ($banners as $banner)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <img src="{{ asset($banner->imagen) }}" class="d-block w-100" alt="{{ $banner->titulo }}">
                        <div class="carousel-caption-custom">
                            @if (!empty($banner->titulo))
                                <h2>{{ $banner->titulo }}</h2>
                            @endif
                            @if (!empty($banner->subtitulo))
                                <p>{{ $banner->subtitulo }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselInstitucion" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselInstitucion" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    @endif

    {{-- ── Hero institucional ── --}}
    <section class="hero-section">
        <div class="container position-relative" style="z-index: 1;">
            <div class="hero-badge">Bienvenido a nuestra institución</div>
            <hr class="inst-divider">

            @if (!empty($configuracion->logo))
                <img src="{{ asset($configuracion->logo) }}" alt="Logo" class="hero-logo-img">
            @else
                <div class="hero-logo-fallback">
                    {{ strtoupper(substr($configuracion->nombre ?? 'IE', 0, 2)) }}
                </div>
            @endif

            @if (!empty($configuracion->nombre))
                <h1>
                    <span class="accent">{{ $configuracion->nombre }}</span>
                </h1>

                <p class="hero-desc">{{ $configuracion->descripcion }}</p>
            @endif
            <div class="hero-btns">
                <a href="{{ route('login') }}" class="btn-hero-primary">Iniciar sesión</a>
                <a href="{{ route('contacto') }}" class="btn-hero-secondary">Contáctanos</a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="stat-num">800+</div>
                    <div class="stat-lbl">Estudiantes</div>
                </div>
                <div>
                    <div class="stat-num">60</div>
                    <div class="stat-lbl">Docentes</div>
                </div>
                <div>
                    <div class="stat-num">25</div>
                    <div class="stat-lbl">Años de historia</div>
                </div>
            </div>

        </div>
    </section>

    {{-- ── Misión / Visión / Info ── --}}
    <section class="inst-section">
        <div class="container">

            <p class="section-eyebrow">Quiénes somos</p>
            <h2 class="section-title">Nuestra institución</h2>
            <p class="section-desc">Conoce los pilares que guían nuestra comunidad educativa día a día.</p>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="pilar-card mision">
                        <span class="pilar-num">01</span>
                        <span class="pilar-tag">Misión</span>
                        <h4>Formamos personas íntegras</h4>
                        <p>{{ Str::limit($configuracion->mision ?? '', 160) }}</p>
                        <a href="{{ route('nosotros') }}" class="pilar-link">Leer más →</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pilar-card vision">
                        <span class="pilar-num">02</span>
                        <span class="pilar-tag">Visión</span>
                        <h4>Referente de calidad regional</h4>
                        <p>{{ Str::limit($configuracion->vision ?? '', 160) }}</p>
                        <a href="{{ route('nosotros') }}" class="pilar-link">Leer más →</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pilar-card info">
                        <span class="pilar-num">03</span>
                        <span class="pilar-tag">Sistema</span>
                        <h4>Gestión académica integral</h4>
                        <p>Plataforma digital para la administración, seguimiento y comunicación institucional.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <hr class="inst-divider">

    {{-- ── Últimas Noticias ── --}}
    <section class="inst-section-alt">
        <div class="container">

            <div class="section-header">
                <div>
                    <p class="section-eyebrow mb-0">Novedades</p>
                    <h2 class="section-title mb-0">Últimas noticias</h2>
                </div>
                <a href="{{ route('noticias') }}" class="ver-todo-link">Ver todas →</a>
            </div>

            <div class="row g-3">
                {{-- Noticia principal (primera) --}}
                @if ($ultimasNoticias->count() > 0)
                    @php $principal = $ultimasNoticias->first(); @endphp
                    <div class="col-md-5">
                        <div class="noticia-principal">
                            @if (!empty($principal->imagen))
                                <img src="{{ asset($principal->imagen) }}" alt="{{ $principal->titulo }}"
                                    class="noticia-principal-img">
                            @else
                                <div class="noticia-principal-img-placeholder"></div>
                            @endif
                            <div class="noticia-principal-body">
                                <span class="noticia-tag">Destacado</span>
                                <div class="noticia-date">{{ $principal->fecha_publicacion }}</div>
                                <h5>{{ $principal->titulo }}</h5>
                                <p>{{ Str::limit($principal->resumen, 120) }}</p>
                                <a href="{{ route('noticias.show', $principal->id) }}" class="btn-inst-sm">Leer más</a>
                            </div>
                        </div>
                    </div>

                    {{-- Noticias secundarias --}}
                    <div class="col-md-7">
                        <div class="row g-3">
                            @foreach ($ultimasNoticias->skip(1) as $noticia)
                                <div class="col-6">
                                    <div class="noticia-card">
                                        @if (!empty($noticia->imagen))
                                            <img src="{{ asset($noticia->imagen) }}" alt="{{ $noticia->titulo }}">
                                        @else
                                            <div class="noticia-card-img-placeholder"></div>
                                        @endif
                                        <div class="noticia-card-body">
                                            <div class="noticia-date">{{ $noticia->fecha_publicacion }}</div>
                                            <h6>{{ Str::limit($noticia->titulo, 60) }}</h6>
                                            <a href="{{ route('noticias.show', $noticia->id) }}"
                                                class="pilar-link mt-2 d-block"
                                                style="font-size:12px; color:var(--color-secundario);">
                                                Leer →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- Fin noticias secundarias --}}
                @endif

            </div>

        </div>
    </section>

    <hr class="inst-divider">

    {{-- ── Galería ── --}}
    <section class="inst-section">
        <div class="container">

            <div class="section-header">
                <div>
                    <p class="section-eyebrow mb-0">Momentos</p>
                    <h2 class="section-title mb-0">Galería institucional</h2>
                </div>
                <a href="{{ route('galeria') }}" class="ver-todo-link">Ver galería →</a>
            </div>

            <div class="row g-3">
                @foreach ($ultimasGalerias as $galeria)
                    <div class="col-md-4">
                        <div class="galeria-item">
                            <img src="{{ asset($galeria->imagen) }}" alt="{{ $galeria->titulo }}">
                            <div class="galeria-overlay">
                                <h5>{{ $galeria->titulo }}</h5>
                                <p>{{ Str::limit($galeria->descripcion, 70) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endsection
