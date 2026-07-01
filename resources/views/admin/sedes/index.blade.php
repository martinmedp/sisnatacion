@extends('adminlte::page')

@section('title', 'Sedes')

@section('content_header')
    <h1>Sedes</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de sedes</h3>
            <div class="card-tools">
                <a href="{{ route('admin.sedes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva sede
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Encargado</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sedes as $sede)
                        <tr>
                            <td>{{ $sede->id }}</td>
                            <td>{{ $sede->nombre }}</td>
                            <td>{{ $sede->direccion }}</td>
                            <td>{{ $sede->telefono }}</td>
                            <td>{{ $sede->encargado }}</td>
                            <td>
                                @if ($sede->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sedes.edit', $sede->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sedes.destroy', $sede->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="sede" style="display:inline;">
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
                            <td colspan="7" class="text-center">No hay sedes registradas.</td>
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
