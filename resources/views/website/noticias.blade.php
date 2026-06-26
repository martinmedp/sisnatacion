@extends('layouts.website')

@section('content')
    {{-- ── Hero de sección ──────────────────────────────────────────── --}}
    <section class="hero-section">
        <span class="hero-badge">{{ $configuracion->nombre ?? 'Sistema Web' }}</span>
        <h1>Noti<span class="accent">cias</span></h1>
        <p class="hero-desc">
            Mantente informado sobre las actividades,
            eventos y novedades de nuestra institución.
        </p>
    </section>

    <div class="inst-divider"></div>

    {{-- ── Grid de noticias ─────────────────────────────────────────── --}}
    <section class="inst-section">
        <div class="container">

            <div class="row g-4">
                @forelse ($noticias as $noticia)
                    <div class="col-md-4">

                        <div class="noticia-principal h-100">

                            @if (!empty($noticia->imagen))
                                <img src="{{ asset($noticia->imagen) }}" alt="{{ $noticia->titulo }}"
                                    class="noticia-principal-img">
                            @else
                                <div class="noticia-principal-img-placeholder"></div>
                            @endif

                            <div class="noticia-principal-body">
                                <span class="noticia-tag">Noticia</span>
                                <span class="noticia-date">{{ $noticia->fecha_publicacion }}</span>
                                <h5>{{ $noticia->titulo }}</h5>
                                <p>{{ Str::limit($noticia->resumen, 120) }}</p>
                                <div class="mt-auto">
                                    <a href="{{ route('noticias.show', $noticia->id) }}" class="btn-inst-sm">
                                        Leer más
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>
                @empty
                    <div class="col-12">
                        <div class="galeria-empty">
                            <span class="galeria-empty-icon">📰</span>
                            <p>No hay noticias publicadas.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
@endsection
