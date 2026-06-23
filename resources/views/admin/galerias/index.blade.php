@extends('adminlte::page')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Administración de Galería
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.galerias.create') }}" class="btn btn-primary">
                    Nueva Imagen Galeria
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th width="180">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($galerias as $galeria)
                        <tr>
                            <td>
                                {{ $galeria->id }}
                            </td>
                            <td width="150">
                                <img src="{{ asset($galeria->imagen) }}" style="max-height:80px;">
                            </td>
                            <td>
                                {{ $galeria->titulo }}
                            </td>
                            <td>
                                {{ $galeria->orden }}
                            </td>
                            <td>
                                {{ $galeria->estado }}
                            </td>
                            <td>
                                <a href="{{ route('admin.galerias.edit', $galeria->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.galerias.destroy', $galeria->id) }}" method="POST"
                                    class="form-eliminar" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.form-eliminar')
            .forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar imagen?',
                        text: 'Esta acción no se puede deshacer',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
    </script>
@stop
