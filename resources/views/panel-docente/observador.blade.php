@extends('adminlte::page')

@section('title', 'Observador — ' . ($alumno->nombre_completo ?? ''))

@section('content_header')
    <h1>Libro observador — {{ $alumno->nombre_completo ?? '—' }}</h1>
@stop

@section('content')

    <a href="javascript:history.back()" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nueva anotación</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('docente.observador.store', $alumno->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value="comportamiento">Comportamiento</option>
                                <option value="conducta">Conducta</option>
                                <option value="rendimiento">Rendimiento</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" rows="5" class="form-control"
                                placeholder="Describe la situación observada..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar anotación
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Historial de anotaciones</h3>
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
