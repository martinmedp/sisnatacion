@extends('adminlte::page')

@section('title', 'Observador de ' . ($alumno->nombre_completo ?? 'alumno'))

@section('content_header')
    <h1>Libro observador — {{ $alumno->nombre_completo }}</h1>
@stop

@section('content')

    <a href="{{ route('acudiente.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Anotaciones registradas por los docentes</h3>
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
                            <td colspan="4" class="text-center">No hay anotaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop
