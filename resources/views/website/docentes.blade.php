@extends('layouts.website')

@section('content')
    {{-- ── Hero de sección ──────────────────────────────────────────── --}}
    <section class="hero-section">
        <span class="hero-badge">{{ $configuracion->nombre ?? 'Sistema Web' }}</span>
        <h1>Equipo <span class="accent">Docente</span></h1>
        <p class="hero-desc">
            Conoce a los profesionales que forman parte de nuestra institución.
        </p>
    </section>

    <div class="inst-divider"></div>

    {{-- ── Grid de docentes ─────────────────────────────────────────── --}}
    <section class="inst-section">
        <div class="container">

            <div class="row g-4">
                @forelse ($docentes as $docente)
                    <div class="col-md-4">

                        <div class="docente-card h-100">

                            @if ($docente->foto)
                                <img src="{{ asset($docente->foto) }}" alt="{{ $docente->nombre_completo }}"
                                    class="docente-foto">
                            @else
                                <div class="docente-foto-placeholder">
                                    <span>{{ strtoupper(substr($docente->nombre_completo, 0, 1)) }}</span>
                                </div>
                            @endif

                            <div class="docente-body">
                                <span class="noticia-tag">{{ $docente->cargo }}</span>
                                <h5 class="docente-nombre">{{ $docente->nombre_completo }}</h5>

                                @if ($docente->profesion)
                                    <div class="docente-meta">
                                        <span class="contacto-label">Profesión</span>
                                        <span class="docente-meta-valor">{{ $docente->profesion }}</span>
                                    </div>
                                @endif

                                @if ($docente->nivel_academico)
                                    <div class="docente-meta">
                                        <span class="contacto-label">Nivel académico</span>
                                        <span class="docente-meta-valor">{{ $docente->nivel_academico }}</span>
                                    </div>
                                @endif

                                @if ($docente->perfil)
                                    <p class="docente-perfil">{{ Str::limit($docente->perfil, 180) }}</p>
                                @endif
                            </div>

                        </div>

                    </div>
                @empty
                    <div class="col-12">
                        <div class="galeria-empty">
                            <span class="galeria-empty-icon">👤</span>
                            <p>No hay docentes disponibles actualmente.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
@endsection
