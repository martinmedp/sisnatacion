@extends('adminlte::page')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Editar Noticia
            </h3>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.noticias.update', $noticia->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Título</label>

                            <input type="text" name="titulo" class="form-control" value="{{ $noticia->titulo }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha publicación</label>

                            <input type="date" name="fecha_publicacion" class="form-control"
                                value="{{ $noticia->fecha_publicacion }}">
                        </div>
                    </div>

                </div>

                <div class="form-group">

                    <label>Resumen</label>

                    <textarea name="resumen" class="form-control" rows="3">{{ $noticia->resumen }}</textarea>

                </div>

                <div class="form-group">

                    <label>Contenido</label>

                    <textarea name="contenido" class="form-control" rows="8">{{ $noticia->contenido }}</textarea>

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

                                <option value="ACTIVO" {{ $noticia->estado == 'ACTIVO' ? 'selected' : '' }}>
                                    ACTIVO
                                </option>

                                <option value="INACTIVO" {{ $noticia->estado == 'INACTIVO' ? 'selected' : '' }}>
                                    INACTIVO
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-3 text-center">

                        <label>Imagen actual</label>

                        <br>

                        <img id="preview" src="{{ asset($noticia->imagen) }}" class="img-fluid" style="max-height:180px;">

                    </div>

                </div>

                <br>

                <a href="{{ route('admin.noticias.index') }}" class="btn btn-secondary">

                    Cancelar

                </a>

                <button type="submit" class="btn btn-primary">

                    Actualizar Noticia

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
        }
    </script>

@stop
