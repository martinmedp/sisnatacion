@extends('adminlte::page')

@section('title', 'Asistencia')

@section('content_header')
    <h1>Asistencia</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Consultar asistencia por grupo</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.asistencia.index') }}" method="GET" class="form-inline">
                <select name="grupo_id" class="form-control mr-2" style="min-width:280px;">
                    <option value="">-- Seleccionar grupo --</option>
                    @foreach ($grupos as $g)
                        <option value="{{ $g->id }}" {{ $grupoId == $g->id ? 'selected' : '' }}>
                            {{ $g->nombre }} — {{ $g->nivel->nombre ?? '' }} ({{ $g->sede->nombre ?? '' }})
                        </option>
                    @endforeach
                </select>

                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control mr-2">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Consultar
                </button>
            </form>

            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle"></i> Esta pantalla es solo de consulta. La asistencia la marca
                cada docente desde su propio panel.
            </small>
        </div>
    </div>

    @if ($grupo)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $grupo->nombre }} — {{ $grupo->nivel->nombre ?? '—' }} ({{ $grupo->sede->nombre ?? '—' }})
                    — Docente: {{ $grupo->docente->nombre_completo ?? '—' }}
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th class="text-center">Estado — {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($matriculas as $matricula)
                            @php $asistencia = $matricula->asistencias->first(); @endphp
                            <tr>
                                <td>{{ $matricula->alumno->nombre_completo ?? '—' }}</td>
                                <td class="text-center">
                                    @if (!$asistencia)
                                        <span class="badge badge-secondary">Sin registrar</span>
                                    @elseif ($asistencia->estado === 'presente')
                                        <span class="badge badge-success">Presente</span>
                                    @elseif ($asistencia->estado === 'ausente')
                                        <span class="badge badge-danger">Ausente</span>
                                    @elseif ($asistencia->estado === 'tarde')
                                        <span class="badge badge-warning">Tarde</span>
                                    @else
                                        <span class="badge badge-info">Excusa</span>
                                    @endif
                                </td>
                                <td>{{ $asistencia->observaciones ?? '—' }}</td>
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
    @endif

@stop
