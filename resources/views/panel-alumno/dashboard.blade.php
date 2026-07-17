@extends('adminlte::page')

@section('title', 'Mi Panel')

@section('content_header')
    <h1>Bienvenido, {{ $alumno->nombre_completo ?? auth()->user()->name }}</h1>
@stop

@section('content')

    @if (!$alumno)
        <div class="alert alert-warning">
            Tu usuario no está vinculado todavía a una ficha de alumno. Contacta al administrador.
        </div>
    @else
        <a href="{{ route('alumno.avance') }}" class="btn btn-info mb-3">
            <i class="fas fa-chart-line"></i> Ver mi avance académico
        </a>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mi información</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Código</td>
                                <td class="text-right">
                                    <span class="badge badge-primary">{{ $alumno->codigo ?? '—' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Acudiente</td>
                                <td class="text-right">{{ $alumno->acudiente->nombre_completo ?? '—' }}</td>
                            </tr>
                            @if ($matriculaActiva)
                                <tr>
                                    <td>Nivel</td>
                                    <td class="text-right">{{ $matriculaActiva->grupo->nivel->nombre ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Grupo</td>
                                    <td class="text-right">{{ $matriculaActiva->grupo->nombre ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Sede</td>
                                    <td class="text-right">{{ $matriculaActiva->grupo->sede->nombre ?? '—' }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mi próxima cuota</h3>
                    </div>
                    <div class="card-body">
                        @if ($proximaCuota)
                            <table class="table table-sm">
                                <tr>
                                    <td>Cuota</td>
                                    <td class="text-right">
                                        #{{ $proximaCuota->numero_cuota }} / {{ $matriculaActiva->numero_cuotas }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Vencimiento</td>
                                    <td class="text-right">
                                        {{ \Carbon\Carbon::parse($proximaCuota->fecha_vencimiento)->format('d/m/Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Valor</td>
                                    <td class="text-right">${{ number_format($proximaCuota->valor, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Estado</td>
                                    <td class="text-right">
                                        @if ($proximaCuota->estado === 'vencido')
                                            <span class="badge badge-danger">Vencido</span>
                                        @elseif ($proximaCuota->estado === 'parcial')
                                            <span class="badge badge-info">Parcial</span>
                                        @else
                                            <span class="badge badge-warning">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @else
                            <p class="text-success mb-0"><i class="fas fa-check-circle"></i> Estás al día con tus pagos.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@stop
