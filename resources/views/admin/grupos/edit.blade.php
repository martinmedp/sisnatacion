@extends('adminlte::page')

@section('title', 'Editar grupo')

@section('content_header')
    <h1>Editar grupo — {{ $grupo->nombre }}</h1>
@stop

@section('content')

    <div class="row">
        {{-- Formulario del grupo --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Datos del grupo</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.grupos.update', $grupo->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label>Nombre del grupo</label>
                                <input type="text" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $grupo->nombre) }}">
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Cupo máximo</label>
                                <input type="number" name="cupo_maximo" min="1" max="100"
                                    class="form-control @error('cupo_maximo') is-invalid @enderror"
                                    value="{{ old('cupo_maximo', $grupo->cupo_maximo) }}">
                                @error('cupo_maximo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Nivel</label>
                                <select name="nivel_id" class="form-control @error('nivel_id') is-invalid @enderror">
                                    @foreach ($niveles as $nivel)
                                        <option value="{{ $nivel->id }}"
                                            {{ old('nivel_id', $grupo->nivel_id) == $nivel->id ? 'selected' : '' }}>
                                            {{ $nivel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sede</label>
                                <select name="sede_id" class="form-control @error('sede_id') is-invalid @enderror">
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id }}"
                                            {{ old('sede_id', $grupo->sede_id) == $sede->id ? 'selected' : '' }}>
                                            {{ $sede->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Docente</label>
                                <select name="docente_id" class="form-control @error('docente_id') is-invalid @enderror">
                                    @foreach ($docentes as $docente)
                                        <option value="{{ $docente->id }}"
                                            {{ old('docente_id', $grupo->docente_id) == $docente->id ? 'selected' : '' }}>
                                            {{ $docente->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" rows="3"
                                class="form-control">{{ old('descripcion', $grupo->descripcion) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="activo" {{ old('estado', $grupo->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $grupo->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                        <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Horarios del grupo --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Horarios</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.horarios.create') }}?grupo_id={{ $grupo->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($grupo->horarios as $horario)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-clock text-info mr-1"></i>
                                    <strong>{{ ucfirst($horario->dia_semana) }}</strong>
                                    {{ date('g:i a', strtotime($horario->hora_inicio)) }}
                                    — {{ date('g:i a', strtotime($horario->hora_fin)) }}
                                </span>
                                <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST"
                                    class="form-eliminar" data-nombre="horario">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Sin horarios asignados</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: '{{ session('success') }}',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
            });
        </script>
    @endif

@stop
