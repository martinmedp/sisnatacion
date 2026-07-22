@extends('adminlte::page')

@section('title', 'Asistencia — ' . $grupo->nombre)

@section('content_header')
    <h1>Asistencia — {{ $grupo->nombre }}</h1>
@stop

@section('content')

    <a href="{{ route('docente.grupos.alumnos', $grupo->id) }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <a href="{{ route('docente.asistencia.resumen', $grupo->id) }}" class="btn btn-info mb-3">
        <i class="fas fa-chart-bar"></i> Ver resumen mensual
    </a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Seleccionar fecha</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('docente.asistencia.index', $grupo->id) }}" method="GET" class="form-inline">
                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control mr-2"
                    onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <form action="{{ route('docente.asistencia.store', $grupo->id) }}" method="POST">
        @csrf
        <input type="hidden" name="fecha" value="{{ $fecha }}">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Marcar asistencia — {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th class="text-center">Presente</th>
                            <th class="text-center">Ausente</th>
                            <th class="text-center">Tarde</th>
                            <th class="text-center">Excusa</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($matriculas as $matricula)
                            @php
                                $asistenciaHoy = $matricula->asistencias->first();
                                $estadoActual = $asistenciaHoy->estado ?? 'presente';
                            @endphp
                            <tr>
                                <td>{{ $matricula->alumno->nombre_completo ?? '—' }}</td>
                                <td class="text-center">
                                    <input type="radio" name="estados[{{ $matricula->id }}]" value="presente"
                                        {{ $estadoActual == 'presente' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="radio" name="estados[{{ $matricula->id }}]" value="ausente"
                                        {{ $estadoActual == 'ausente' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="radio" name="estados[{{ $matricula->id }}]" value="tarde"
                                        {{ $estadoActual == 'tarde' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="radio" name="estados[{{ $matricula->id }}]" value="excusa"
                                        {{ $estadoActual == 'excusa' ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="text" name="observaciones[{{ $matricula->id }}]"
                                        value="{{ $asistenciaHoy->observaciones ?? '' }}"
                                        class="form-control form-control-sm" placeholder="Opcional">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay alumnos matriculados activos en este grupo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($matriculas->count() > 0)
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar asistencia
                    </button>
                @endif
            </div>
        </div>
    </form>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'success', title: 'Correcto', text: '{{ session('success') }}',
                    timer: 1500, timerProgressBar: true, showConfirmButton: false
                });
            });
        </script>
    @endif

@stop
