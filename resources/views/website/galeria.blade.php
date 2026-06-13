@extends('layouts.website')

@section('content')
    <div class="mb-4 text-center">
        <h1>
            Galería Institucional
        </h1>
        <p class="text-muted">
            Conoce algunos de los momentos más importantes
            de nuestra comunidad educativa.
        </p>
    </div>
    <div class="row">
        @forelse ($galerias as $galeria)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ asset($galeria->imagen) }}" alt="{{ $galeria->titulo }}" class="card-img-top"
                        style="height:250px; object-fit:cover;">
                    <div class="card-body">
                        <h5>
                            {{ $galeria->titulo }}
                        </h5>
                        @if (!empty($galeria->descripcion))
                            <p class="text-muted">
                                {{ $galeria->descripcion }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    No hay imágenes disponibles.
                </div>
            </div>
        @endforelse
    </div>
@endsection
