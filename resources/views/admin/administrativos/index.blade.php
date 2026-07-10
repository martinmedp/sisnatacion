@extends('adminlte::page')

@section('title', 'Administrativos')

@section('content_header')
    <h1>Administrativos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de administrativos</h3>
            <div class="card-tools">
                <a href="{{ route('admin.administrativos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo administrativo
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administrativos.index') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="buscar" value="{{ $buscar }}" class="form-control mr-2"
                    style="width: 300px;" placeholder="Buscar por nombre...">
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if ($buscar)
                    <a href="{{ route('admin.administrativos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Sede</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($administrativos as $administrativo)
                        <tr>
                            <td width="60">
                                @if ($administrativo->foto)
                                    <img src="{{ asset($administrativo->foto) }}"
                                        style="width:40px;height:40px;object-fit:cover;border-radius:50%;">
                                @else
                                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                @endif
                            </td>
                            <td>{{ $administrativo->nombre_completo }}</td>
                            <td>{{ $administrativo->cargo->nombre ?? '—' }}</td>
                            <td>{{ $administrativo->sede->nombre ?? 'General' }}</td>
                            <td>{{ $administrativo->telefono }}</td>
                            <td>{{ $administrativo->correo }}</td>
                            <td>
                                @if ($administrativo->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.administrativos.edit', $administrativo->id) }}"
                                    class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.administrativos.destroy', $administrativo->id) }}"
                                    method="POST" class="form-eliminar" data-nombre="administrativo"
                                    style="display:inline;">
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
                            <td colspan="8" class="text-center">
                                @if ($buscar)
                                    No se encontraron administrativos con "{{ $buscar }}".
                                @else
                                    No hay administrativos registrados.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('success'))
        <script>
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    text: '{{ session('success') }}',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

@stop
