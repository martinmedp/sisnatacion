@extends('adminlte::page')

@section('title', 'Horarios')

@section('content_header')
    <h1>Horarios</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de horarios</h3>
            <div class="card-tools">
                <a href="{{ route('admin.horarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo horario
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Grupo</th>
                        <th>Nivel</th>
                        <th>Sede</th>
                        <th>Docente</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($horarios as $horario)
                        <tr>
                            <td>{{ ucfirst($horario->dia_semana) }}</td>
                            <td>
                                {{ date('g:i a', strtotime($horario->hora_inicio)) }}
                                — {{ date('g:i a', strtotime($horario->hora_fin)) }}
                            </td>
                            <td>{{ $horario->grupo->nombre ?? '—' }}</td>
                            <td>{{ $horario->grupo->nivel->nombre ?? '—' }}</td>
                            <td>{{ $horario->grupo->sede->nombre ?? '—' }}</td>
                            <td>{{ $horario->grupo->docente->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($horario->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.horarios.edit', $horario->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="horario" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay horarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: '{{ session('success') }}',
            });
            });
        </script>
    @endif

@stop
