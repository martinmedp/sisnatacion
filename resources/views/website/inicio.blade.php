@extends('layouts.website')

@section('content')
    <h2>Bienvenidos</h2>

    <p>
        {{ $configuracion->descripcion }}
    </p>

    <hr>

    <h3>Información Institucional</h3>

    <p>
        <strong>Dirección:</strong>
        {{ $configuracion->direccion }}
    </p>

    <p>
        <strong>Teléfono:</strong>
        {{ $configuracion->telefono1 }}
    </p>

    <p>
        <strong>Correo:</strong>
        {{ $configuracion->correo_electronico }}
    </p>

    <br>

    <a href="{{ route('login') }}">
        Iniciar Sesión
    </a>

    @if (Route::has('register'))
        |
        <a href="{{ route('register') }}">
            Registrarse
        </a>
    @endif
@endsection
