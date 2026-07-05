@extends('adminlte::page')

@section('title', 'Editar acudiente')

@section('content_header')
    <h1>Editar acudiente</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.acudientes.update', $acudiente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>Nombre completo</label>
                        <input type="text" name="nombre_completo"
                            class="form-control @error('nombre_completo') is-invalid @enderror"
                            value="{{ old('nombre_completo', $acudiente->nombre_completo) }}">
                        @error('nombre_completo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Parentesco</label>
                        <select name="parentesco" class="form-control @error('parentesco') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach(['Padre','Madre','Abuelo/a','Tío/a','Hermano/a','Tutor legal','Otro'] as $p)
                                <option value="{{ $p }}" {{ old('parentesco', $acudiente->parentesco) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('parentesco')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Tipo de documento</label>
                        <select name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach(['CC' => 'Cédula de ciudadanía', 'CE' => 'Cédula de extranjería', 'PA' => 'Pasaporte'] as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('tipo_documento', $acudiente->tipo_documento) == $key ? 'selected' : '' }}>
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
                            value="{{ old('numero_documento', $acudiente->numero_documento) }}">
                        @error('numero_documento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Teléfono</label>
                        <input type="text" name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono', $acudiente->telefono) }}">
                        @error('telefono')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                            value="{{ old('correo', $acudiente->correo) }}">
                        @error('correo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                            value="{{ old('direccion', $acudiente->direccion) }}">
                        @error('direccion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones</label>
                    <textarea name="observaciones" rows="3"
                        class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $acudiente->observaciones) }}</textarea>
                    @error('observaciones')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                        <option value="activo" {{ old('estado', $acudiente->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $acudiente->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.acudientes.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@stop
