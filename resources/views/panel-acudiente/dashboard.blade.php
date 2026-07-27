@extends('adminlte::page')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ $acudiente->nombre_completo ?? auth()->user()->name }}</h1>
@stop

@section('content')

    @if (!$acudiente)
        <div class="alert alert-warning">
            Tu usuario no está vinculado todavía a una ficha de acudiente. Contacta al administrador.
        </div>
    @else
        @forelse ($alumnos as $alumno)
            @php
                $matriculaActiva = $alumno->matriculas->first();
                $proximaCuota = $matriculaActiva
                    ? $matriculaActiva->cobros->sortBy('numero_cuota')->first(fn ($c) => $c->estado !== 'pagado')
                    : null;
                $rutaAvance = route('acudiente.avance', $alumno->id);
                $rutaObservador = route('acudiente.observador', $alumno->id);
            @endphp

            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $alumno->nombre_completo }}
                    </h3>
                </div>
                <div class="card-body">
                    @include('partials.resumen-alumno', ['mostrarAcudiente' => false])
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted">
                    No tienes alumnos registrados a tu cargo.
                </div>
            </div>
        @endforelse
    @endif

@stop
