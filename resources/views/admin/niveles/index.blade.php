@extends('adminlte::page')

@section('title', 'Niveles')

@section('content_header')
    <h1>Niveles</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de niveles</h3>
            <div class="card-tools">
                <a href="{{ route('admin.niveles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo nivel
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Valor por clase</th>
                        <th>Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($niveles as $nivel)
                        <tr>
                            <td>{{ $nivel->orden }}</td>
                            <td>{{ $nivel->nombre }}</td>
                            <td>
                                @if ($nivel->edad_minima || $nivel->edad_maxima)
                                    {{ $nivel->edad_minima }} - {{ $nivel->edad_maxima }} años
                                @else
                                    —
                                @endif
                            </td>
                            <td>${{ number_format($nivel->valor_clase, 0, ',', '.') }}</td>
                            <td>
                                @if ($nivel->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.niveles.edit', $nivel->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.niveles.destroy', $nivel->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="nivel" style="display:inline;">
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
                            <td colspan="6" class="text-center">No hay niveles registrados.</td>
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
