@extends('adminlte::page')

@section('content')

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Editar Imagen
            </h3>

        </div>

        <div class="card-body">

            <form action="{{ route('admin.galerias.update', $galeria->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Título</label>

                            <input type="text" name="titulo" class="form-control" value="{{ $galeria->titulo }}">

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>Orden</label>

                            <input type="number" name="orden" class="form-control" value="{{ $galeria->orden }}">

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>Estado</label>

                            <select name="estado" class="form-control">

                                <option value="ACTIVO" {{ $galeria->estado == 'ACTIVO' ? 'selected' : '' }}>
                                    ACTIVO
                                </option>

                                <option value="INACTIVO" {{ $galeria->estado == 'INACTIVO' ? 'selected' : '' }}>
                                    INACTIVO
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="form-group">

                    <label>Descripción</label>

                    <textarea name="descripcion" rows="3" class="form-control">{{ $galeria->descripcion }}</textarea>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Nueva imagen</label>

                            <input type="file" name="imagen" class="form-control" accept="image/*"
                                onchange="mostrarImagen(event)">

                        </div>

                    </div>

                    <div class="col-md-6 text-center">

                        <label>Imagen actual</label>

                        <br>

                        <img id="preview" src="{{ asset($galeria->imagen) }}" class="img-fluid" style="max-height:250px;">

                    </div>

                </div>

                <br>

                <a href="{{ route('admin.galerias.index') }}" class="btn btn-secondary">

                    Cancelar

                </a>

                <button type="submit" class="btn btn-primary">

                    Actualizar Imagen

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
