@extends('adminlte::page')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ $alumno->nombre_completo ?? auth()->user()->name }}</h1>
@stop

@section('content')

    @if (!$alumno)
        <div class="alert alert-warning">
            Tu usuario no está vinculado todavía a una ficha de alumno. Contacta al administrador.
        </div>
    @else
        @php $rutaAvance = route('alumno.avance'); $rutaObservador = route('alumno.observador'); @endphp
        @include('partials.resumen-alumno')
    @endif

@stop
