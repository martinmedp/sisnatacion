@extends('adminlte::page')

@section('title', 'Resumen de asistencia — ' . $grupo->nombre)

@section('content_header')
    <h1>Resumen mensual de asistencia — {{ $grupo->nombre }}</h1>
@stop

@section('content')

    <a href="{{ route('docente.grupos.alumnos', $grupo->id) }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Seleccionar mes</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('docente.asistencia.resumen', $grupo->id) }}" method="GET" class="form-inline">
                <input type="month" name="mes" value="{{ $mes }}" class="form-control mr-2"
                    onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ ucfirst($inicioMes->translatedFormat('F Y')) }}
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th class="text-center">Clases del mes</th>
                        <th class="text-center">Presentes</th>
                        <th class="text-center">Ausencias</th>
                        <th class="text-center">Tardanzas</th>
                        <th class="text-center">Excusas</th>
                        <th class="text-center">Novedad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resumen as $fila)
                        <tr>
                            <td>{{ $fila['alumno']->nombre_completo ?? '—' }}</td>
                            <td class="text-center">{{ $fila['clasesDelMes'] }}</td>
                            <td class="text-center text-success">{{ $fila['presentes'] }}</td>
                            <td class="text-center {{ $fila['ausentes'] > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ $fila['ausentes'] }}
                            </td>
                            <td class="text-center {{ $fila['tardanzas'] > 0 ? 'text-warning font-weight-bold' : '' }}">
                                {{ $fila['tardanzas'] }}
                            </td>
                            <td class="text-center">{{ $fila['excusas'] }}</td>
                            <td class="text-center">
                                @if ($fila['clasesDelMes'] === 0)
                                    <span class="badge badge-secondary">Sin registros</span>
                                @elseif ($fila['ausentes'] > 0)
                                    <span class="badge badge-danger">{{ $fila['ausentes'] }} ausencia(s)</span>
                                @elseif ($fila['tardanzas'] > 0)
                                    <span class="badge badge-warning">{{ $fila['tardanzas'] }} tardanza(s)</span>
                                @else
                                    <span class="badge badge-success">Al día</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay alumnos matriculados activos en este grupo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop
