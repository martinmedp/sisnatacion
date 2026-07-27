@extends('adminlte::page')

@section('title', 'Editar administrativo')

@section('content_header')
    <h1>Editar administrativo</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.administrativos.update', $administrativo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Datos personales</h5>

                <div class="form-group text-center">
                    <div class="mb-2">
                        <img id="preview-foto"
                            src="{{ $administrativo->foto ? asset($administrativo->foto) : asset('vendor/adminlte/dist/img/user4-128x128.jpg') }}"
                            style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:2px solid #dee2e6;">
                    </div>
                    <label>Foto {{ $administrativo->foto ? '(selecciona una nueva para reemplazarla)' : '' }}</label>
                    <input type="file" name="foto" id="input-foto" accept="image/*"
                        class="form-control-file @error('foto') is-invalid @enderror"
                        onchange="previsualizarFoto(this)">
                    @error('foto')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>Nombre completo</label>
                        <input type="text" name="nombre_completo"
                            class="form-control @error('nombre_completo') is-invalid @enderror"
                            value="{{ old('nombre_completo', $administrativo->nombre_completo) }}">
                        @error('nombre_completo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                            class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                            value="{{ old('fecha_nacimiento', $administrativo->fecha_nacimiento) }}">
                        @error('fecha_nacimiento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Tipo de documento</label>
                        <select name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach(['CC' => 'Cédula de ciudadanía', 'CE' => 'Cédula de extranjería', 'TI' => 'Tarjeta de identidad', 'PA' => 'Pasaporte'] as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('tipo_documento', $administrativo->tipo_documento) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_documento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Número de documento</label>
                        <input type="text" name="numero_documento"
                            class="form-control @error('numero_documento') is-invalid @enderror"
                            value="{{ old('numero_documento', $administrativo->numero_documento) }}">
                        @error('numero_documento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Teléfono</label>
                        <input type="text" name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono', $administrativo->telefono) }}">
                        @error('telefono')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                        value="{{ old('correo', $administrativo->correo) }}">
                    @error('correo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                <h5 class="mb-3">Datos laborales</h5>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Cargo</label>
                        <select name="cargo_id" class="form-control @error('cargo_id') is-invalid @enderror">
                            <option value="">-- Seleccionar cargo --</option>
                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->id }}"
                                    {{ old('cargo_id', $administrativo->cargo_id) == $cargo->id ? 'selected' : '' }}>
                                    {{ $cargo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cargo_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Sede</label>
                        <select name="sede_id" class="form-control @error('sede_id') is-invalid @enderror">
                            <option value="">-- General (sin sede específica) --</option>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}"
                                    {{ old('sede_id', $administrativo->sede_id) == $sede->id ? 'selected' : '' }}>
                                    {{ $sede->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('sede_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha de ingreso</label>
                        <input type="date" name="fecha_ingreso"
                            class="form-control @error('fecha_ingreso') is-invalid @enderror"
                            value="{{ old('fecha_ingreso', $administrativo->fecha_ingreso) }}">
                        @error('fecha_ingreso')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Contacto de emergencia y observaciones</h5>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Contacto de emergencia</label>
                        <input type="text" name="contacto_emergencia"
                            class="form-control @error('contacto_emergencia') is-invalid @enderror"
                            value="{{ old('contacto_emergencia', $administrativo->contacto_emergencia) }}">
                        @error('contacto_emergencia')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Teléfono de emergencia</label>
                        <input type="text" name="telefono_emergencia"
                            class="form-control @error('telefono_emergencia') is-invalid @enderror"
                            value="{{ old('telefono_emergencia', $administrativo->telefono_emergencia) }}">
                        @error('telefono_emergencia')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones (EPS, ARL, otras anotaciones)</label>
                    <textarea name="observaciones" rows="3"
                        class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $administrativo->observaciones) }}</textarea>
                    @error('observaciones')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                        <option value="activo" {{ old('estado', $administrativo->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $administrativo->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.administrativos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>

            @if ($administrativo->user_id)
                <hr>
                <button type="button" class="btn btn-warning btn-sm" onclick="confirmarRestablecerClave()">
                    <i class="fas fa-key"></i> Restablecer contraseña
                </button>
                <small class="text-muted d-block mt-1">
                    Genera una nueva contraseña temporal para la cuenta de acceso de este administrativo.
                </small>

                <form id="form-restablecer-clave" action="{{ route('admin.administrativos.restablecerClave', $administrativo->id) }}" method="POST" style="display:none;">
                    @csrf
                    @method('PUT')
                </form>
            @else
                <hr>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i> Este administrativo no tiene cuenta de acceso (sin correo registrado).
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

    <script>
        function previsualizarFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-foto').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
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
