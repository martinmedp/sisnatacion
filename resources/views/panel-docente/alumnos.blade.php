@extends('adminlte::page')

@section('title', 'Alumnos — ' . $grupo->nombre)

@section('content_header')
    <h1>Alumnos del grupo {{ $grupo->nombre }}</h1>
@stop

@section('content')

    <a href="{{ route('docente.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a mis grupos
    </a>

    @if ($grupo->horarios->isEmpty())
        <button type="button" class="btn btn-secondary mb-3" disabled title="El grupo no tiene horarios asignados">
            <i class="fas fa-calendar-times"></i> Marcar asistencia (no disponible)
        </button>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Este grupo no tiene horarios asignados. Pide al administrador que configure al menos un horario
            para poder registrar asistencia.
        </div>
    @else
        <a href="{{ route('docente.asistencia.index', $grupo->id) }}" class="btn btn-success mb-3">
            <i class="fas fa-calendar-check"></i> Marcar asistencia de hoy
        </a>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ $grupo->nivel->nombre ?? '—' }} — {{ $grupo->sede->nombre ?? '—' }}
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Código</th>
                        <th width="260">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matriculas as $matricula)
                        <tr>
                            <td>{{ $matricula->alumno->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($matricula->alumno->codigo ?? null)
                                    <span class="badge badge-primary">{{ $matricula->alumno->codigo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('docente.logros.index', $matricula->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-award"></i> Logros
                                </a>
                                <a href="{{ route('docente.observador.index', $matricula->alumno_id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-book"></i> Observador
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay alumnos matriculados activos en este grupo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('error'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

@stop
