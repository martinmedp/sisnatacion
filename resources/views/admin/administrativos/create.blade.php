@extends('adminlte::page')

@section('title', 'Nuevo administrativo')

@section('content_header')
    <h1>Nuevo administrativo</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.administrativos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h5 class="mb-3">Datos personales</h5>

                <div class="form-group text-center">
                    <div class="mb-2">
                        <img id="preview-foto" src="{{ asset('vendor/adminlte/dist/img/user4-128x128.jpg') }}"
                            style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:2px solid #dee2e6;">
                    </div>
                    <label>Foto</label>
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
                            value="{{ old('nombre_completo') }}">
                        @error('nombre_completo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                            class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                            value="{{ old('fecha_nacimiento') }}">
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
                            <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de ciudadanía</option>
                            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de extranjería</option>
                            <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de identidad</option>
                            <option value="PA" {{ old('tipo_documento') == 'PA' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        @error('tipo_documento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Número de documento</label>
                        <input type="text" name="numero_documento"
                            class="form-control @error('numero_documento') is-invalid @enderror"
                            value="{{ old('numero_documento') }}">
                        @error('numero_documento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Teléfono</label>
                        <input type="text" name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono') }}">
                        @error('telefono')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                        value="{{ old('correo') }}">
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
                                <option value="{{ $cargo->id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
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
                                <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>
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
                            value="{{ old('fecha_ingreso') }}">
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
                            value="{{ old('contacto_emergencia') }}" placeholder="Nombre del contacto">
                        @error('contacto_emergencia')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Teléfono de emergencia</label>
                        <input type="text" name="telefono_emergencia"
                            class="form-control @error('telefono_emergencia') is-invalid @enderror"
                            value="{{ old('telefono_emergencia') }}">
                        @error('telefono_emergencia')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones (EPS, ARL, otras anotaciones)</label>
                    <textarea name="observaciones" rows="3"
                        class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('admin.administrativos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

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

@stop
