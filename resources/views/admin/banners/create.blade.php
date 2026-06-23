@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Nuevo Banner
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" class="form-control" name="titulo" placeholder="Título del banner">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subtítulo</label>
                            <input type="text" class="form-control" name="subtitulo" placeholder="Subtítulo del banner">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Imagen</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*"
                                onchange="mostrarImagen(event)">
                        </div>
                        <small class="text-muted">Tamaño recomendado: 1920x600 px. - Formatos: JPG, PNG, WEBP, AVIF. -
                            Máximo 4 MB.
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vista previa</label>
                            <div class="border rounded p-3 text-center">
                                <img id="preview" src="" class="img-fluid"
                                    style="max-height:250px; display:none;">
                                <span id="textoPreview">
                                    Sin imagen
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Texto Botón</label>
                            <input type="text" name="texto_boton" class="form-control" placeholder="Más información">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>URL Botón</label>
                            <input type="text" name="url_boton" class="form-control" placeholder="/admisiones">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Orden</label>
                            <input type="number" name="orden" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control" name="estado">
                                <option>
                                    ACTIVO
                                </option>
                                <option>
                                    INACTIVO
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">Guardar Banner</button>
            </form>
        </div>
    </div>
    <script>
        function mostrarImagen(event) {
            let archivo = event.target.files[0];
            if (!archivo) {
                return;
            }
            let preview = document.getElementById('preview');
            let texto = document.getElementById('textoPreview');
            preview.src = URL.createObjectURL(archivo);
            preview.style.display = 'block';
            texto.style.display = 'none';
        }
    </script>
@stop
