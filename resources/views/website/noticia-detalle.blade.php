@extends('layouts.website')

@section('content')
    <div class="mb-4">
        <a href="{{ route('noticias') }}" class="btn btn-outline-secondary">
            ← Volver a Noticias
        </a>
    </div>
    <article class="card shadow-sm">
        @if (!empty($noticia->imagen))
            <img src="{{ asset($noticia->imagen) }}" alt="{{ $noticia->titulo }}" class="card-img-top"
                style="max-height:500px; object-fit:cover;">
        @endif
        <div class="card-body">
            <small class="text-muted">
                {{ $noticia->fecha_publicacion }}
            </small>
            <h1 class="mt-2 mb-4">
                {{ $noticia->titulo }}
            </h1>
            <div>
                {!! nl2br(e($noticia->contenido)) !!}
            </div>
        </div>
    </article>
@endsection
