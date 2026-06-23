@extends('layouts.website')

@section('content')
    <div class="text-center mb-5">
        <h1>
            Contáctanos
        </h1>
        <p class="text-muted">
            Estamos disponibles para atender tus consultas e inquietudes.
        </p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-4">
                        {{ $configuracion->nombre }}
                    </h3>
                    <div class="mb-3">
                        <strong>Dirección:</strong><br>
                        {{ $configuracion->direccion }}
                    </div>
                    <div class="mb-3">
                        <strong>Teléfono:</strong><br>
                        {{ $configuracion->telefono1 }}
                    </div>
                    <div class="mb-3">
                        <strong>Correo Electrónico:</strong><br>
                        <a href="mailto:{{ $configuracion->correo_electronico }}">
                            {{ $configuracion->correo_electronico }}
                        </a>
                    </div>
                    <div class="mb-3">
                        <strong>Página Web:</strong><br>
                        <a href="{{ $configuracion->web }}" target="_blank">
                            {{ $configuracion->web }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
