{{-- FOTO --}}
<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">
            Fotografía
        </h3>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>Fotografía</label>

                    <input type="file" name="foto" class="form-control" accept="image/*"
                        onchange="mostrarImagen(event)">

                </div>

            </div>

            <div class="col-md-6 text-center">

                @if (isset($docente) && $docente->foto)
                    <img id="preview" src="{{ asset($docente->foto) }}" class="img-fluid" style="max-height:200px;">

                    <span id="textoPreview" style="display:none;">
                        Sin fotografía
                    </span>
                @else
                    <img id="preview" src="" class="img-fluid" style="max-height:200px; display:none;">

                    <span id="textoPreview">
                        Sin fotografía
                    </span>
                @endif

            </div>

        </div>

    </div>

</div>


{{-- DATOS PERSONALES --}}
<div class="card card-secondary">

    <div class="card-header">
        <h3 class="card-title">
            Datos Personales
        </h3>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>Nombre Completo</label>

                    <input type="text" name="nombre_completo" class="form-control"
                        value="{{ old('nombre_completo', $docente->nombre_completo ?? '') }}">

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-group">

                    <label>Tipo Documento</label>

                    <select name="tipo_documento" class="form-control">

                        <option {{ old('tipo_documento', $docente->tipo_documento ?? '') == 'CC' ? 'selected' : '' }}>
                            CC
                        </option>

                        <option {{ old('tipo_documento', $docente->tipo_documento ?? '') == 'CE' ? 'selected' : '' }}>
                            CE
                        </option>

                        <option {{ old('tipo_documento', $docente->tipo_documento ?? '') == 'TI' ? 'selected' : '' }}>
                            TI
                        </option>

                        <option
                            {{ old('tipo_documento', $docente->tipo_documento ?? '') == 'Pasaporte' ? 'selected' : '' }}>
                            Pasaporte
                        </option>

                    </select>

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-group">

                    <label>Número Documento</label>

                    <input type="text" name="numero_documento" class="form-control"
                        value="{{ old('numero_documento', $docente->numero_documento ?? '') }}">

                </div>

            </div>

        </div>
        <div class="row">

            <div class="col-md-4">

                <div class="form-group">

                    <label>Fecha Nacimiento</label>

                    <input type="date" name="fecha_nacimiento" class="form-control"
                        value="{{ old('fecha_nacimiento', $docente->fecha_nacimiento ?? '') }}">

                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group">

                    <label>Estado Civil</label>

                    <select name="estado_civil" class="form-control">

                        <option
                            {{ old('estado_civil', $docente->estado_civil ?? '') == 'Soltero(a)' ? 'selected' : '' }}>
                            Soltero(a)
                        </option>

                        <option
                            {{ old('estado_civil', $docente->estado_civil ?? '') == 'Casado(a)' ? 'selected' : '' }}>
                            Casado(a)
                        </option>

                        <option
                            {{ old('estado_civil', $docente->estado_civil ?? '') == 'Unión Libre' ? 'selected' : '' }}>
                            Unión Libre
                        </option>

                        <option
                            {{ old('estado_civil', $docente->estado_civil ?? '') == 'Divorciado(a)' ? 'selected' : '' }}>
                            Divorciado(a)
                        </option>

                        <option
                            {{ old('estado_civil', $docente->estado_civil ?? '') == 'Viudo(a)' ? 'selected' : '' }}>
                            Viudo(a)
                        </option>

                    </select>

                </div>

            </div>

        </div>
    </div>

</div>

{{-- CONTACTO --}}
<div class="card card-info">

    <div class="card-header">
        <h3 class="card-title">
            Información de Contacto
        </h3>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <div class="form-group">

                    <label>Teléfono</label>

                    <input type="text" name="telefono" class="form-control"
                        value="{{ old('telefono', $docente->telefono ?? '') }}">

                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group">

                    <label>Correo Personal</label>

                    <input type="email" name="correo_personal" class="form-control"
                        value="{{ old('correo_personal', $docente->correo_personal ?? '') }}">

                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group">

                    <label>Correo Institucional</label>

                    <input type="email" name="correo_institucional" class="form-control"
                        value="{{ old('correo_institucional', $docente->correo_institucional ?? '') }}">

                </div>

            </div>

        </div>

        <div class="form-group">

            <label>Dirección</label>

            <input type="text" name="direccion" class="form-control"
                value="{{ old('direccion', $docente->direccion ?? '') }}">

        </div>

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>Contacto de Emergencia</label>

                    <input type="text" name="contacto_emergencia" class="form-control"
                        value="{{ old('contacto_emergencia', $docente->contacto_emergencia ?? '') }}">

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label>Teléfono Emergencia</label>

                    <input type="text" name="telefono_emergencia" class="form-control"
                        value="{{ old('telefono_emergencia', $docente->telefono_emergencia ?? '') }}">

                </div>

            </div>

        </div>

    </div>

</div>
{{-- FORMACIÓN ACADÉMICA --}}
<div class="card card-success">

    <div class="card-header">
        <h3 class="card-title">
            Formación Académica
        </h3>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>Profesión</label>

                    <input type="text" name="profesion" class="form-control"
                        value="{{ old('profesion', $docente->profesion ?? '') }}">

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label>Nivel Académico</label>

                    <input type="text" name="nivel_academico" class="form-control"
                        value="{{ old('nivel_academico', $docente->nivel_academico ?? '') }}">

                </div>

            </div>

        </div>

    </div>

</div>
{{-- INFORMACIÓN INSTITUCIONAL --}}
<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">
            Información Institucional
        </h3>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <div class="form-group">

                    <label>Cargo</label>

                    <input type="text" name="cargo" class="form-control"
                        value="{{ old('cargo', $docente->cargo ?? '') }}">

                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group">

                    <label>Fecha Ingreso</label>

                    <input type="date" name="fecha_ingreso" class="form-control"
                        value="{{ old('fecha_ingreso', $docente->fecha_ingreso ?? '') }}">

                </div>

            </div>

            <div class="col-md-2">

                <div class="form-group">

                    <label>Orden</label>

                    <input type="number" name="orden" class="form-control"
                        value="{{ old('orden', $docente->orden ?? 1) }}">

                </div>

            </div>

            <div class="col-md-2">

                <div class="form-group">

                    <label>Estado</label>

                    <select name="estado" class="form-control">

                        <option {{ old('estado', $docente->estado ?? 'ACTIVO') == 'ACTIVO' ? 'selected' : '' }}>
                            ACTIVO
                        </option>

                        <option {{ old('estado', $docente->estado ?? '') == 'INACTIVO' ? 'selected' : '' }}>
                            INACTIVO
                        </option>

                    </select>

                </div>

            </div>

        </div>

        <div class="form-group">

            <label>Perfil Profesional</label>

            <textarea name="perfil" rows="5" class="form-control">{{ old('perfil', $docente->perfil ?? '') }}</textarea>

        </div>

        <div class="form-group mt-3">

            <label>Observaciones</label>

            <textarea name="observaciones" rows="4" class="form-control">{{ old('observaciones', $docente->observaciones ?? '') }}</textarea>

        </div>

    </div>

</div>

<div class="mt-3">

    <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">

        Cancelar

    </a>

    <button type="submit" class="btn btn-primary">

        Guardar Docente

    </button>

</div>

<script>
    function mostrarImagen(event) {
        let archivo = event.target.files[0];

        if (!archivo) {
            return;
        }

        document.getElementById('preview').src =
            URL.createObjectURL(archivo);

        document.getElementById('preview').style.display =
            'block';

        document.getElementById('textoPreview').style.display =
            'none';
    }
</script>
