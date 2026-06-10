@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Nueva Noticia
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.noticias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha publicación</label>
                            <input type="date" name="fecha_publicacion" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Resumen</label>
                    <textarea name="resumen" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Contenido</label>
                    <textarea name="contenido" class="form-control" rows="8"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*"
                                onchange="mostrarImagen(event)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option>
                                    ACTIVO
                                </option>
                                <option>
                                    INACTIVO
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <label>Vista previa</label>
                        <br>
                        <img id="preview" src="" class="img-fluid" style="max-height:180px; display:none;">
                        <span id="textoPreview">
                            Sin imagen
                        </span>
                    </div>
                </div>
                <br>
                <a href="{{ route('admin.noticias.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    Guardar Noticia
                </button>
            </form>
        </div>
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

@stop
