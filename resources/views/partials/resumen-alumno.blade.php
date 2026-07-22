{{-- Tarjeta de resumen de un alumno — usada tanto en el dashboard del
     propio alumno como, repetida una vez por hijo, en el del acudiente.
     Espera en scope: $alumno, $matriculaActiva, $proximaCuota, $rutaAvance
     y opcionalmente $mostrarAcudiente (bool, default true). --}}

@php
    $mostrarAcudiente = $mostrarAcudiente ?? true;
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Información</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Código</td>
                        <td class="text-right">
                            <span class="badge badge-primary">{{ $alumno->codigo ?? '—' }}</span>
                        </td>
                    </tr>
                    @if ($mostrarAcudiente)
                        <tr>
                            <td>Acudiente</td>
                            <td class="text-right">{{ $alumno->acudiente->nombre_completo ?? '—' }}</td>
                        </tr>
                    @endif
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
                    @else
                        <tr>
                            <td colspan="2" class="text-muted text-center">Sin matrícula activa actualmente.</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Próxima cuota</h3>
            </div>
            <div class="card-body">
                @if ($proximaCuota)
                    <table class="table table-sm mb-3">
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
                    <p class="text-success mb-3"><i class="fas fa-check-circle"></i> Al día con los pagos.</p>
                @endif

                @if ($rutaAvance ?? false)
                    <a href="{{ $rutaAvance }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-line"></i> Ver avance académico
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
