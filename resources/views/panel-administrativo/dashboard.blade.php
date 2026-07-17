@extends('adminlte::page')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ $administrativo->nombre_completo ?? auth()->user()->name }}</h1>
@stop

@section('content')

    @if (!$administrativo)
        <div class="alert alert-warning">
            Tu usuario no está vinculado todavía a una ficha de administrativo. Contacta al administrador.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mi información</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Cargo</td>
                        <td class="text-right">{{ $administrativo->cargo->nombre ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td>Sede</td>
                        <td class="text-right">{{ $administrativo->sede->nombre ?? 'General' }}</td>
                    </tr>
                    <tr>
                        <td>Correo</td>
                        <td class="text-right">{{ $administrativo->correo ?? '—' }}</td>
                    </tr>
                </table>
                <p class="text-muted mb-0">
                    Este panel se irá ampliando con las funciones propias de tu cargo.
                </p>
            </div>
        </div>
    @endif

@stop
