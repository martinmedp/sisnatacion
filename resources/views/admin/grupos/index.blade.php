@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>Grupos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de grupos</h3>
            <div class="card-tools">
                <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo grupo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nivel</th>
                        <th>Sede</th>
                        <th>Docente</th>
                        <th>Cupo</th>
                        <th>Horarios</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grupos as $grupo)
                        <tr>
                            <td>{{ $grupo->nombre }}</td>
                            <td>{{ $grupo->nivel->nombre ?? '—' }}</td>
                            <td>{{ $grupo->sede->nombre ?? '—' }}</td>
                            <td>{{ $grupo->docente->nombre_completo ?? '—' }}</td>
                            <td>{{ $grupo->cupo_maximo }}</td>
                            <td>
                                @forelse ($grupo->horarios as $h)
                                    <span class="badge badge-info">
                                        {{ ucfirst($h->dia_semana) }}
                                        {{ date('g:i a', strtotime($h->hora_inicio)) }}
                                        — {{ date('g:i a', strtotime($h->hora_fin)) }}
                                    </span>
                                @empty
                                    <span class="text-muted">Sin horario</span>
                                @endforelse
                            </td>
                            <td>
                                @if ($grupo->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.grupos.edit', $grupo->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.grupos.destroy', $grupo->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="grupo" style="display:inline;">
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
                            <td colspan="8" class="text-center">No hay grupos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: '{{ session('success') }}',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

@stop
