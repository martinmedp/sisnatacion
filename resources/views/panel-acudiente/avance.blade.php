@extends('adminlte::page')

@section('title', 'Avance de ' . ($alumno->nombre_completo ?? 'alumno'))

@section('content_header')
    <h1>Avance académico de {{ $alumno->nombre_completo }}</h1>
@stop

@section('content')

    <a href="{{ route('acudiente.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    @if ($matriculas->isEmpty())
        <div class="alert alert-info">
            Este alumno aún no tiene niveles registrados.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selecciona un nivel cursado</h3>
            </div>
            <div class="card-body">
                <select id="selector-nivel" class="form-control" style="max-width: 500px;">
                    @foreach ($matriculas as $matricula)
                        <option value="nivel-{{ $matricula->id }}">
                            {{ $matricula->grupo->nivel->nombre ?? '—' }}
                            — {{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}
                            —
                            @if ($matricula->resultado_final === 'aprobado')
                                Aprobado
                            @elseif ($matricula->resultado_final === 'reprobado')
                                Reprobado
                            @else
                                En curso
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @foreach ($matriculas as $matricula)
            @php
                $totalCriterios = $matricula->evaluaciones->count();
                $logrados = $matricula->evaluaciones->where('estado_criterio', 'logrado')->count();
                $porcentaje = $totalCriterios > 0 ? round(($logrados / $totalCriterios) * 100) : 0;
            @endphp
            <div class="card bloque-nivel" id="nivel-{{ $matricula->id }}" style="display:none;">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $matricula->grupo->nivel->nombre ?? '—' }}
                        @if ($matricula->resultado_final === 'aprobado')
                            <span class="badge badge-success ml-2">Aprobado</span>
                        @elseif ($matricula->resultado_final === 'reprobado')
                            <span class="badge badge-danger ml-2">Reprobado</span>
                        @else
                            <span class="badge badge-warning ml-2">En curso</span>
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Grupo: {{ $matricula->grupo->nombre ?? '—' }} —
                        Matriculado el {{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}
                    </p>

                    <div class="progress mb-3" style="height: 22px;">
                        <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%">
                            {{ $logrados }} / {{ $totalCriterios }} criterios logrados
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Criterio</th>
                                <th class="text-center" width="160">Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matricula->evaluaciones->sortBy(fn($e) => $e->criterio->orden ?? 0) as $evaluacion)
                                <tr>
                                    <td>{{ $evaluacion->criterio->orden ?? '—' }}</td>
                                    <td>{{ $evaluacion->criterio->nombre ?? '—' }}</td>
                                    <td class="text-center">
                                        @if ($evaluacion->estado_criterio === 'logrado')
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Logrado</span>
                                        @elseif ($evaluacion->estado_criterio === 'en_proceso')
                                            <span class="badge badge-info">En proceso</span>
                                        @else
                                            <span class="badge badge-secondary">No logrado</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <script>
            function mostrarNivelSeleccionado() {
                document.querySelectorAll('.bloque-nivel').forEach(function (bloque) {
                    bloque.style.display = 'none';
                });
                var seleccionado = document.getElementById('selector-nivel').value;
                var bloque = document.getElementById(seleccionado);
                if (bloque) {
                    bloque.style.display = 'block';
                }
            }

            document.getElementById('selector-nivel').addEventListener('change', mostrarNivelSeleccionado);
            window.addEventListener('load', mostrarNivelSeleccionado);
        </script>
    @endif

@stop
