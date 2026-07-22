@extends('adminlte::page')

@section('title', 'Logros — ' . ($matricula->alumno->nombre_completo ?? ''))

@section('content_header')
    <h1>Logros de {{ $matricula->alumno->nombre_completo ?? '—' }}</h1>
@stop

@section('content')

    <a href="{{ route('docente.grupos.alumnos', $matricula->grupo_id) }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    @php
        $totalCriterios = $matricula->evaluaciones->count();
        $logrados = $matricula->evaluaciones->where('estado_criterio', 'logrado')->count();
        $porcentaje = $totalCriterios > 0 ? round(($logrados / $totalCriterios) * 100) : 0;
    @endphp

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Nivel: {{ $matricula->grupo->nivel->nombre ?? '—' }}
            </h3>
        </div>
        <div class="card-body">
            <div class="progress mb-3" style="height: 22px;">
                <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%">
                    {{ $logrados }} / {{ $totalCriterios }} criterios logrados
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Criterio</th>
                        <th width="200">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matricula->evaluaciones->sortBy(fn($e) => $e->criterio->orden ?? 0) as $evaluacion)
                        <tr>
                            <td>{{ $evaluacion->criterio->orden ?? '—' }}</td>
                            <td>{{ $evaluacion->criterio->nombre ?? '—' }}</td>
                            <td>
                                <form action="{{ route('docente.logros.update', $evaluacion->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="estado_criterio" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="no_logrado" {{ $evaluacion->estado_criterio == 'no_logrado' ? 'selected' : '' }}>No logrado</option>
                                        <option value="en_proceso" {{ $evaluacion->estado_criterio == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                                        <option value="logrado" {{ $evaluacion->estado_criterio == 'logrado' ? 'selected' : '' }}>Logrado</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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
