@extends('layouts.website')

@section('content')
    <div class="mb-4">
        <h1>Noticias</h1>
        <p class="text-muted">
            Mantente informado sobre las actividades,
            eventos y novedades de nuestra institución.
        </p>
    </div>
    <div class="row">
        @forelse ($noticias as $noticia)
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
                        <h5 class="card-title">
                            {{ $noticia->titulo }}
                        </h5>
                        <p class="card-text">
                            {{ Str::limit($noticia->resumen, 120) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('noticias.show', $noticia->id) }}" class="btn btn-primary">
                                Leer más
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    No hay noticias publicadas.
                </div>
            </div>
        @endforelse
    </div>
@endsection
