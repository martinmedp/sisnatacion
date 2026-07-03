@extends('adminlte::page')

@section('title', 'Nuevo grupo')

@section('content_header')
    <h1>Nuevo grupo</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.grupos.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>Nombre del grupo</label>
                        <input type="text" name="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}"
                            placeholder="Ej. Principiantes 1">
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Cupo máximo</label>
                        <input type="number" name="cupo_maximo" min="1" max="100"
                            class="form-control @error('cupo_maximo') is-invalid @enderror"
                            value="{{ old('cupo_maximo', 15) }}">
                        @error('cupo_maximo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Nivel</label>
                        <select name="nivel_id" class="form-control @error('nivel_id') is-invalid @enderror">
                            <option value="">-- Seleccionar nivel --</option>
                            @foreach ($niveles as $nivel)
                                <option value="{{ $nivel->id }}" {{ old('nivel_id') == $nivel->id ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('nivel_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Sede</label>
                        <select name="sede_id" class="form-control @error('sede_id') is-invalid @enderror">
                            <option value="">-- Seleccionar sede --</option>
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
                        <label>Docente</label>
                        <select name="docente_id" class="form-control @error('docente_id') is-invalid @enderror">
                            <option value="">-- Seleccionar docente --</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('docente_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3"
                        class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
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
                <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@stop
