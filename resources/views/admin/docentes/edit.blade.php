@extends('adminlte::page')

@section('content')

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Editar Docente
            </h3>

        </div>

        <div class="card-body">

            <form action="{{ route('admin.docentes.update', $docente->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('admin.docentes.form')

            </form>

            @if ($docente->user_id)
                <hr>
                <button type="button" class="btn btn-warning btn-sm" onclick="confirmarRestablecerClave()">
                    <i class="fas fa-key"></i> Restablecer contraseña
                </button>
                <small class="text-muted d-block mt-1">
                    Genera una nueva contraseña temporal para la cuenta de acceso de este docente.
                </small>

                <form id="form-restablecer-clave" action="{{ route('admin.docentes.restablecerClave', $docente->id) }}" method="POST" style="display:none;">
                    @csrf
                    @method('PUT')
                </form>
            @else
                <hr>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i> Este docente no tiene cuenta de acceso (sin correo registrado).
                </p>
            @endif

        </div>

    </div>

    <script>
        function confirmarRestablecerClave() {
            Swal.fire({
                title: '¿Restablecer contraseña?',
                text: 'Se generará una nueva clave temporal para este usuario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, restablecer',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ffc107',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-restablecer-clave').submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'success', title: 'Correcto', text: '{{ session('success') }}'
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
