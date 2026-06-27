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
                @foreach ($docentes as $docente)
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
    <script>
        document.querySelectorAll('.form-eliminar')
            .forEach(form => {
                form.addEventListener(
                    'submit',
                    function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '¿Eliminar docente?',
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
                    }
                );
            });
    </script>
@stop
