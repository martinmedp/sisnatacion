@extends('adminlte::page')

@section('title', 'Acudientes')

@section('content_header')
    <h1>Acudientes</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de acudientes</h3>
            <div class="card-tools">
                <a href="{{ route('admin.acudientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo acudiente
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Parentesco</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Alumnos a cargo</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acudientes as $acudiente)
                        <tr>
                            <td>{{ $acudiente->nombre_completo }}</td>
                            <td>{{ $acudiente->numero_documento }}</td>
                            <td>{{ $acudiente->parentesco }}</td>
                            <td>{{ $acudiente->telefono }}</td>
                            <td>{{ $acudiente->correo }}</td>
                            <td>
                                <span class="badge badge-info">{{ $acudiente->alumnos_count }}</span>
                            </td>
                            <td>
                                @if ($acudiente->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.acudientes.edit', $acudiente->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.acudientes.destroy', $acudiente->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="acudiente" style="display:inline;">
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
                            <td colspan="8" class="text-center">No hay acudientes registrados.</td>
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
