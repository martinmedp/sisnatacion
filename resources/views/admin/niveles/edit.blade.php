@extends('adminlte::page')

@section('title', 'Editar nivel')

@section('content_header')
    <h1>Editar nivel</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.niveles.update', $nivel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre', $nivel->nombre) }}">
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Orden</label>
                        <input type="number" name="orden" min="0"
                            class="form-control @error('orden') is-invalid @enderror"
                            value="{{ old('orden', $nivel->orden) }}">
                        @error('orden')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $nivel->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Edad mínima</label>
                        <input type="number" name="edad_minima" min="0"
                            class="form-control @error('edad_minima') is-invalid @enderror"
                            value="{{ old('edad_minima', $nivel->edad_minima) }}">
                        @error('edad_minima')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Edad máxima</label>
                        <input type="number" name="edad_maxima" min="0"
                            class="form-control @error('edad_maxima') is-invalid @enderror"
                            value="{{ old('edad_maxima', $nivel->edad_maxima) }}">
                        @error('edad_maxima')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Valor por clase</label>
                        <input type="number" name="valor_clase" step="0.01" min="0"
                            class="form-control @error('valor_clase') is-invalid @enderror"
                            value="{{ old('valor_clase', $nivel->valor_clase) }}">
                        @error('valor_clase')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                        <option value="activo" {{ old('estado', $nivel->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $nivel->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.niveles.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@stop
