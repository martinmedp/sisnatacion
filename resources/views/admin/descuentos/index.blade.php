@extends('adminlte::page')

@section('title', 'Descuentos')

@section('content_header')
    <h1>Descuentos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de descuentos</h3>
            <div class="card-tools">
                <a href="{{ route('admin.descuentos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo descuento
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($descuentos as $descuento)
                        <tr>
                            <td>{{ $descuento->id }}</td>
                            <td>{{ $descuento->nombre }}</td>
                            <td>
                                @if ($descuento->tipo === 'porcentaje')
                                    <span class="badge badge-info">Porcentaje</span>
                                @else
                                    <span class="badge badge-primary">Valor fijo</span>
                                @endif
                            </td>
                            <td>
                                @if ($descuento->tipo === 'porcentaje')
                                    {{ $descuento->valor }}%
                                @else
                                    ${{ number_format($descuento->valor, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if ($descuento->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.descuentos.edit', $descuento->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.descuentos.destroy', $descuento->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="descuento" style="display:inline;">
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
                            <td colspan="6" class="text-center">No hay descuentos registrados.</td>
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
