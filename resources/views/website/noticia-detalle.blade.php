@extends('layouts.website')

@section('content')
    {{-- ── Imagen principal ─────────────────────────────────────────── --}}
    @if (!empty($noticia->imagen))
        <div class="noticia-hero-img">
            <img src="{{ asset($noticia->imagen) }}" alt="{{ $noticia->titulo }}">
            <div class="galeria-overlay">
                <span class="noticia-tag">Noticia</span>
                <h1>{{ $noticia->titulo }}</h1>
                <p class="noticia-date">{{ $noticia->fecha_publicacion }}</p>
            </div>
        </div>
    @else
        <section class="hero-section">
            <span class="hero-badge">Noticia</span>
            <h1>{{ $noticia->titulo }}</h1>
            <p class="hero-desc">{{ $noticia->fecha_publicacion }}</p>
        </section>
    @endif

    <div class="inst-divider"></div>

    {{-- ── Contenido ────────────────────────────────────────────────── --}}
    <section class="inst-section">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <a href="{{ route('noticias') }}" class="ver-todo-link mb-4 d-inline-block">
                        ← Volver a Noticias
                    </a>

                    <div class="noticia-contenido">
                        {!! nl2br(e($noticia->contenido)) !!}
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
