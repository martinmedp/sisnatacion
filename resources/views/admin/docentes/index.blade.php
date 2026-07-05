@extends('adminlte::page')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Docentes
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
                    Nuevo Docente
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.docentes.index') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="buscar" value="{{ $buscar }}" class="form-control mr-2"
                    style="width: 300px;" placeholder="Buscar por nombre...">
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if ($buscar)
                    <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($docentes as $docente)
                    <tr>
                        <td>
                            {{ $docente->id }}
                        </td>
                        <td width="120">
                            @if ($docente->foto)
                                <img src="{{ asset($docente->foto) }}" style="max-height:80px;">
                            @endif
                        </td>
                        <td>
                            {{ $docente->nombre_completo }}
                        </td>
                        <td>
                            {{ $docente->cargo }}
                        </td>
                        <td>
                            {{ $docente->telefono }}
                        </td>
                        <td>
                            {{ $docente->estado }}
                        </td>
                        <td>
                            <a href="{{ route('admin.docentes.edit', $docente->id) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.docentes.destroy', $docente->id) }}" method="POST"
                                class="form-eliminar" data-nombre="docente" style="display:inline;">
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
                        <td colspan="7" class="text-center">
                            @if ($buscar)
                                No se encontraron docentes con "{{ $buscar }}".
                            @else
                                No hay docentes registrados.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
