@extends('adminlte::page')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ $docente->nombre_completo ?? auth()->user()->name }}</h1>
@stop

@section('content')

    @if (!$docente)
        <div class="alert alert-warning">
            Tu usuario no está vinculado todavía a una ficha de docente. Contacta al administrador.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mis grupos asignados</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Nivel</th>
                            <th>Sede</th>
                            <th>Horarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grupos as $grupo)
                            <tr>
                                <td>{{ $grupo->nombre }}</td>
                                <td>{{ $grupo->nivel->nombre ?? '—' }}</td>
                                <td>{{ $grupo->sede->nombre ?? '—' }}</td>
                                <td>
                                    @forelse ($grupo->horarios as $horario)
                                        <span class="badge badge-info">
                                            {{ ucfirst($horario->dia_semana) }}
                                            {{ date('g:i a', strtotime($horario->hora_inicio)) }}
                                        </span>
                                    @empty
                                        <span class="text-muted">Sin horario</span>
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No tienes grupos asignados actualmente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@stop
