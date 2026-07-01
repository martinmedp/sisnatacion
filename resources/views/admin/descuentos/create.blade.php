@extends('adminlte::page')

@section('title', 'Nuevo descuento')

@section('content_header')
    <h1>Nuevo descuento</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.descuentos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre') }}" placeholder="Ej. Descuento por hermanos">
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control @error('tipo') is-invalid @enderror">
                            <option value="porcentaje" {{ old('tipo') == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                            <option value="valor_fijo" {{ old('tipo') == 'valor_fijo' ? 'selected' : '' }}>Valor fijo ($)</option>
                        </select>
                        @error('tipo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Valor</label>
                        <input type="number" name="valor" step="0.01" min="0"
                            class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor') }}">
                        @error('valor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
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
                <a href="{{ route('admin.descuentos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@stop
