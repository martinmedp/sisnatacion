@extends('adminlte::page')

@section('title', 'Nuevo horario')

@section('content_header')
    <h1>Nuevo horario</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.horarios.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Grupo</label>
                    <select name="grupo_id" class="form-control @error('grupo_id') is-invalid @enderror">
                        <option value="">-- Seleccionar grupo --</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}"
                                {{ (old('grupo_id') ?? request('grupo_id')) == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                                ({{ $grupo->nivel->nombre ?? '' }} — {{ $grupo->sede->nombre ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('grupo_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Día de la semana</label>
                    <select name="dia_semana" class="form-control @error('dia_semana') is-invalid @enderror">
                        <option value="">-- Seleccionar día --</option>
                        @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $dia)
                            <option value="{{ $dia }}" {{ old('dia_semana') == $dia ? 'selected' : '' }}>
                                {{ ucfirst($dia) }}
                            </option>
                        @endforeach
                    </select>
                    @error('dia_semana')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Hora de inicio</label>
                        <input type="time" name="hora_inicio"
                            class="form-control @error('hora_inicio') is-invalid @enderror"
                            value="{{ old('hora_inicio') }}">
                        @error('hora_inicio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Hora de fin</label>
                        <input type="time" name="hora_fin"
                            class="form-control @error('hora_fin') is-invalid @enderror"
                            value="{{ old('hora_fin') }}">
                        @error('hora_fin')
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
                <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@stop
