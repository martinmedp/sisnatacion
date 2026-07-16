@extends('adminlte::page')

@section('title', 'Criterios de Evaluación')

@section('content_header')
    <h1>Criterios de Evaluación</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de criterios por nivel</h3>
            <div class="card-tools">
                <a href="{{ route('admin.criterios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo criterio
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.criterios.index') }}" method="GET" class="form-inline mb-3">
                <select name="nivel_id" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">-- Todos los niveles --</option>
                    @foreach ($niveles as $nivel)
                        <option value="{{ $nivel->id }}" {{ $nivelId == $nivel->id ? 'selected' : '' }}>
                            {{ $nivel->nombre }}
                        </option>
                    @endforeach
                </select>
                @if ($nivelId)
                    <a href="{{ route('admin.criterios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nivel</th>
                        <th>Criterio</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($criterios as $criterio)
                        <tr>
                            <td>{{ $criterio->orden }}</td>
                            <td><span class="badge badge-info">{{ $criterio->nivel->nombre ?? '—' }}</span></td>
                            <td>{{ $criterio->nombre }}</td>
                            <td>
                                @if ($criterio->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.criterios.edit', $criterio->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.criterios.destroy', $criterio->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="criterio" style="display:inline;">
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
                            <td colspan="5" class="text-center">No hay criterios registrados.</td>
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
                    icon: 'success', title: 'Correcto', text: '{{ session('success') }}',
                    timer: 1500, timerProgressBar: true, showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({ icon: 'warning', title: 'Atención', text: '{{ session('error') }}' });
            });
        </script>
    @endif

@stop
