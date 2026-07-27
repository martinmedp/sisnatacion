@extends('adminlte::page')

@section('title', 'Anotaciones — Observador')

@section('content_header')
    <h1>Anotaciones — Libro observador</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Consultar observador por alumno</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.observador.index') }}" method="GET" class="form-inline">
                <select name="alumno_id" class="form-control mr-2" style="min-width:280px;">
                    <option value="">-- Seleccionar alumno --</option>
                    @foreach ($alumnos as $a)
                        <option value="{{ $a->id }}" {{ $alumnoId == $a->id ? 'selected' : '' }}>
                            {{ $a->nombre_completo }} {{ $a->codigo ? '(' . $a->codigo . ')' : '' }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Consultar
                </button>
            </form>

            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle"></i> Esta pantalla es solo de consulta. Las anotaciones las
                escribe cada docente desde su propio panel.
            </small>
        </div>
    </div>

    @if ($alumno)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $alumno->nombre_completo }}</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alumno->observador as $anotacion)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($anotacion->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $anotacion->tipo === 'comportamiento' ? 'info' : ($anotacion->tipo === 'conducta' ? 'warning' : ($anotacion->tipo === 'rendimiento' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst($anotacion->tipo) }}
                                    </span>
                                </td>
                                <td>{{ $anotacion->descripcion }}</td>
                                <td>{{ $anotacion->docente->nombre_completo ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay anotaciones registradas para este alumno.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@stop
