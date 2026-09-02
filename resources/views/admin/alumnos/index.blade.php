@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
    <h1>Alumnos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de alumnos</h3>
            <div class="card-tools">
                <a href="{{ route('admin.alumnos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo alumno
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.alumnos.index') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="buscar" value="{{ $buscar }}" class="form-control mr-2"
                    style="width: 300px;" placeholder="Buscar por nombre o código...">
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if ($buscar)
                    <a href="{{ route('admin.alumnos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Acudiente</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alumnos as $alumno)
                        <tr>
                            <td width="70">
                                @if ($alumno->foto)
                                    <img src="{{ asset($alumno->foto) }}"
                                        style="width:45px;height:45px;object-fit:cover;border-radius:50%;">
                                @else
                                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                @endif
                            </td>
                            <td>
                                @if ($alumno->codigo)
                                    <span class="badge badge-primary">{{ $alumno->codigo }}</span>
                                @else
                                    <span class="text-muted">Sin matricular</span>
                                @endif
                            </td>
                            <td>{{ $alumno->nombre_completo }}</td>
                            <td>{{ $alumno->edad ?? '—' }}</td>
                            <td>{{ $alumno->acudiente->nombre_completo ?? '—' }}</td>
                            <td>{{ $alumno->telefono }}</td>
                            <td>
                                @if ($alumno->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @elseif ($alumno->autorregistro)
                                    <span class="badge badge-warning">Pendiente de aprobar</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.alumnos.edit', $alumno->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.alumnos.destroy', $alumno->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="alumno" style="display:inline;">
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
                                    No se encontraron alumnos con "{{ $buscar }}".
                                @else
                                    No hay alumnos registrados.
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

    @if (session('error'))
        <script>
            window.addEventListener('load', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se puede eliminar',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

@stop
