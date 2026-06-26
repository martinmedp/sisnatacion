@extends('layouts.website')

@section('content')
    {{-- ── Hero de sección ──────────────────────────────────────────── --}}
    <section class="hero-section">
        <span class="hero-badge">{{ $configuracion->nombre ?? 'Sistema Web' }}</span>
        <h1>Galería <span class="accent">Institucional</span></h1>
        <p class="hero-desc">
            Conoce algunos de los momentos más importantes
            de nuestra comunidad educativa.
        </p>
    </section>

    <div class="inst-divider"></div>

    {{-- ── Grid de galería ──────────────────────────────────────────── --}}
    <section class="inst-section">
        <div class="container">

            <div class="row g-4">
                @forelse ($galerias as $galeria)
                    <div class="col-md-4">
                        <div class="galeria-item">
                            <img src="{{ asset($galeria->imagen) }}" alt="{{ $galeria->titulo }}">
                            <div class="galeria-overlay">
                                <h5>{{ $galeria->titulo }}</h5>
                                @if (!empty($galeria->descripcion))
                                    <p>{{ $galeria->descripcion }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="galeria-empty">
                            <span class="galeria-empty-icon">🖼️</span>
                            <p>No hay imágenes disponibles.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
@endsection
