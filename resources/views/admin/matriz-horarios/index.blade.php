@extends('adminlte::page')

@section('title', 'Matriz de Horarios')

@section('content_header')
    <h1>Matriz de Horarios</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-building"></i> Ver por sede</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Muestra qué franjas están libres u ocupadas en una sede, según los grupos ya asignados.
                    </p>
                    <form action="{{ route('admin.matriz-horarios.irSede') }}" method="GET" class="form-inline">
                        <select name="sede_id" class="form-control mr-2" style="flex:1;">
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-th"></i> Ver matriz
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chalkboard-teacher"></i> Ver por docente</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Muestra la disponibilidad de un docente combinando todas las sedes donde dicta clase.
                    </p>
                    <form action="{{ route('admin.matriz-horarios.irDocente') }}" method="GET" class="form-inline">
                        <select name="docente_id" class="form-control mr-2" style="flex:1;">
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->id }}">{{ $docente->nombre_completo }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-th"></i> Ver matriz
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
