@extends('adminlte::page')

{{-- @section('title', 'Dashboard') --}}

{{-- @section('content_header')
    <h1>Configuración</h1>
@stop --}}

@section('content')
    <!-- Card superior -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <h1>CONFIGURACION DEL SISTEMA</h1>
                    <p class="card-text">
                        Parámetros del sistema, nombre de la aplicación, Email de contacto, y otros ajustes de la
                        plataforma.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Card Datos institución -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos de la Institución</h3>
                </div>
                <div class="card-body ">
                    <form action="{{ route('admin.configuracion.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Datos Institución   -->
                            <div class="col-md-8">
                                <div class="row">
                                    <!-- Nombre -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Nombre:</label><b>(*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('nombre', $configuracion->nombre ?? '') }}" name="nombre"
                                                    placeholder="nombre de la institución" required>
                                            </div>
                                            @error('nombre')
                                                <small style="color: red">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- descripcion -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Descripción</label><b>(*)</b>
                                            <div class="input-group mb3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('descripcion', $configuracion->descripcion ?? '') }}"
                                                    name="descripcion" placeholder="Descripción de la institución">
                                            </div>
                                            @error('descripcion')
                                                <small style="color: red">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                /// Misión, Visión e Historia
                                <!-- Misión y Visión -->
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Misión</label>

                                            <textarea name="mision" class="form-control" rows="4" placeholder="Misión institucional">{{ old('mision', $configuracion->mision ?? '') }}</textarea>

                                            @error('mision')
                                                <small style="color:red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Visión</label>

                                            <textarea name="vision" class="form-control" rows="4" placeholder="Visión institucional">{{ old('vision', $configuracion->vision ?? '') }}</textarea>

                                            @error('vision')
                                                <small style="color:red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <!-- Historia -->
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <label>Historia Institucional</label>

                                            <textarea name="historia" class="form-control" rows="6" placeholder="Historia de la institución">{{ old('historia', $configuracion->historia ?? '') }}</textarea>

                                            @error('historia')
                                                <small style="color:red">{{ $message }}</small>
                                            @enderror

                                        </div>
                                    </div>

                                </div>
                                <!-- Dirección, teléfono y moneda -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Dirección</label><b>(*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-map-marker-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('direccion', $configuracion->direccion ?? '') }}"
                                                    name="direccion" placeholder="Dirección de la institución" required>
                                            </div>
                                            @error('direccion')
                                                <small style="color: red">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Teléfono</label><b>(*)</b>
                                            <div class="input-group mb3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('telefono1', $configuracion->telefono1 ?? '') }}"
                                                    name="telefono1" placeholder="Teléfono de la institución">
                                            </div>
                                            @error('telefono1')
                                                <small style="color: red">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Moneda</label><b>(*)</b>
                                            <div class="input-group mb3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                                </div>
                                                <select name="divisa" id="divisa" class="form-control" required>
                                                    <option value="">Moneda</option>

                                                    <!-- Agrega más opciones de monedas según sea necesario,en configuracionController hacemos lectura
                                                                                                                                                                                                                                                                                de la API y enviamos la información a la vista -->
                                                    @foreach ($divisas as $divisa)
                                                        <option value="{{ $divisa['symbol'] }}"
                                                            {{ old('divisa', $configuracion->divisa ?? '') == $divisa['symbol'] ? 'selected' : '' }}>
                                                            {{ $divisa['name'] }} ({{ $divisa['symbol'] }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('divisa')
                                                <small style="color: red">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Email, Página web -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Correo Electrónico</label><b>(*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control"
                                                    value="{{ old('correo_electronico', $configuracion->correo_electronico ?? '') }}"
                                                    name="correo_electronico" placeholder="Correo electrónico" required>
                                            </div>
                                            @error('correo_electronico')
                                                <small style="color: red">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Página web</label><b>(*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('web', $configuracion->web ?? '') }}" name="web"
                                                    placeholder="Página web de la institución">
                                                @error('web')
                                                    <small style="color: red">{{ $message }} </small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Logo -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Logo Institución</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-university"></i></span>
                                        </div>
                                        <input type="file" class="form-control"
                                            value="{{ old('logo', $configuracion->logo ?? '') }}" name="logo"
                                            placeholder="Logo de la institución" onchange="mostrarImagen(event)"
                                            accept="image/*">
                                    </div>
                                    <small class="text-muted">
                                        Tamaño recomendado: 400x400 px.
                                        Formatos permitidos: PNG, JPG, WEBP, AVIF.
                                    </small>
                                    <br>
                                    {{-- <img src="" id="preview" style="max-width: 200px; margin-top: 10px;"> --}}
                                    <img src="{{ url($configuracion->logo ?? '') }}" id="preview"
                                        class="d-block mx-auto" style="max-width: 250px; margin-top: 10px;">

                                    <script>
                                        const mostrarImagen = e =>
                                            document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
                                    </script>
                                    @error('logo')
                                        <small style="color: red">{{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('/admin', []) }}}" class="btn btn-default">Volver</a>
                                <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
