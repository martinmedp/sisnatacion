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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mis alumnos a cargo</h3>
            </div>
            <div class="card-body">
                @forelse ($alumnos as $alumno)
                    @php $matricula = $alumno->matriculas->first(); @endphp
                    <div class="card card-outline card-info mb-3">
                        <div class="card-body">
                            <h5>
                                {{ $alumno->nombre_completo }}
                                @if ($alumno->codigo)
                                    <span class="badge badge-primary">{{ $alumno->codigo }}</span>
                                @endif
                            </h5>
                            @if ($matricula)
                                <p class="mb-1"><strong>Nivel:</strong> {{ $matricula->grupo->nivel->nombre ?? '—' }}</p>
                                <p class="mb-1"><strong>Grupo:</strong> {{ $matricula->grupo->nombre ?? '—' }}</p>
                                @php
                                    $saldoPendiente = $matricula->cobros->sum(fn($c) => $c->saldo_pendiente);
                                @endphp
                                <p class="mb-0">
                                    <strong>Saldo pendiente:</strong>
                                    <span class="{{ $saldoPendiente > 0 ? 'text-danger' : 'text-success' }}">
                                        ${{ number_format($saldoPendiente, 0, ',', '.') }}
                                    </span>
                                </p>
                            @else
                                <p class="text-muted mb-0">Sin matrícula activa actualmente.</p>
                            @endif
                            <a href="{{ route('acudiente.avance', $alumno->id) }}" class="btn btn-info btn-sm mt-2">
                                <i class="fas fa-chart-line"></i> Ver avance académico
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted mb-0">No tienes alumnos registrados a tu cargo.</p>
                @endforelse
            </div>
        </div>
    @endif

@stop
