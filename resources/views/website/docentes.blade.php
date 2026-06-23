@extends('layouts.website')

@section('content')
    <div class="text-center mb-5">
        <h1>
            Nuestro Equipo Docente
        </h1>
        <p class="text-muted">
            Conoce a los profesionales que forman parte de nuestra institución.
        </p>
    </div>
    <div class="row">
        @forelse ($docentes as $docente)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if ($docente->foto)
                        <img src="{{ asset($docente->foto) }}" class="card-img-top" alt="{{ $docente->nombre_completo }}"
                            style="height:300px; object-fit:cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $docente->nombre_completo }}
                        </h5>
                        <p class="text-primary mb-2">
                            <strong>
                                {{ $docente->cargo }}
                            </strong>
                        </p>
                        @if ($docente->profesion)
                            <p class="mb-2">
                                <strong>Profesión:</strong>
                                {{ $docente->profesion }}
                            </p>
                        @endif
                        @if ($docente->nivel_academico)
                            <p class="mb-2">
                                <strong>Nivel académico:</strong>
                                {{ $docente->nivel_academico }}
                            </p>
                        @endif
                        @if ($docente->perfil)
                            <p>
                                {{ Str::limit($docente->perfil, 180) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info">
                    No hay docentes disponibles actualmente.
                </div>
            </div>
        @endforelse
    </div>
@endsection
