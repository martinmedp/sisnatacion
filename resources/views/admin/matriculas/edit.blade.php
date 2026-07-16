@extends('adminlte::page')

@section('title', 'Detalle de matrícula')

@section('content_header')
    <h1>Detalle de matrícula</h1>
@stop

@section('content')

    @php
        $totalPagado = $matricula->cobros->sum(fn($c) => $c->valor_pagado);
        $totalMatricula = $matricula->valor_total_nivel - $matricula->descuento_aplicado;
        $saldoTotal = $totalMatricula - $totalPagado;
        $cuotasPagadas = $matricula->cobros->where('estado', 'pagado')->count();
    @endphp

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información general</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Alumno</td>
                            <td class="text-right">{{ $matricula->alumno->nombre_completo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Código</td>
                            <td class="text-right">
                                <span class="badge badge-primary">{{ $matricula->alumno->codigo ?? '—' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Grupo</td>
                            <td class="text-right">{{ $matricula->grupo->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Nivel</td>
                            <td class="text-right">{{ $matricula->grupo->nivel->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Sede</td>
                            <td class="text-right">{{ $matricula->grupo->sede->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Fecha de matrícula</td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Periodicidad</td>
                            <td class="text-right">
                                <span class="badge badge-secondary">{{ $matricula->periodicidad_formateada }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Valor total del nivel</td>
                            <td class="text-right">${{ number_format($matricula->valor_total_nivel, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Descuento aplicado</td>
                            <td class="text-right text-danger">
                                -${{ number_format($matricula->descuento_aplicado, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Valor por cuota</td>
                            <td class="text-right">${{ number_format($matricula->valor_cuota, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="mb-2"><i class="fas fa-chart-pie"></i> Resumen de pago</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Cuotas pagadas</td>
                            <td class="text-right">
                                <span class="badge badge-success">{{ $cuotasPagadas }} / {{ $matricula->numero_cuotas }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Total pagado</td>
                            <td class="text-right text-success font-weight-bold">
                                ${{ number_format($totalPagado, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Saldo pendiente total</strong></td>
                            <td class="text-right font-weight-bold {{ $saldoTotal > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($saldoTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>

                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $porcentaje = $totalMatricula > 0 ? round(($totalPagado / $totalMatricula) * 100) : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%">
                            {{ $porcentaje }}%
                        </div>
                    </div>

                    <form action="{{ route('admin.matriculas.update', $matricula->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Estado de la matrícula</label>
                            <select name="estado" class="form-control">
                                <option value="activa" {{ $matricula->estado == 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="finalizada" {{ $matricula->estado == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ $matricula->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save"></i> Actualizar estado
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cuotas de esta matrícula</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Cuota</th>
                                <th>Vencimiento</th>
                                <th>Valor</th>
                                <th>Pagado</th>
                                <th>Saldo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matricula->cobros as $cobro)
                                <tr>
                                    <td>{{ $cobro->numero_cuota }} / {{ $matricula->numero_cuotas }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cobro->fecha_vencimiento)->format('d/m/Y') }}</td>
                                    <td>${{ number_format($cobro->valor, 0, ',', '.') }}</td>
                                    <td class="text-success">${{ number_format($cobro->valor_pagado, 0, ',', '.') }}</td>
                                    <td class="{{ $cobro->saldo_pendiente > 0 ? 'text-danger' : '' }}">
                                        ${{ number_format($cobro->saldo_pendiente, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($cobro->estado === 'pagado')
                                            <span class="badge badge-success">Pagado</span>
                                        @elseif ($cobro->estado === 'parcial')
                                            <span class="badge badge-info">Parcial</span>
                                        @elseif ($cobro->estado === 'vencido')
                                            <span class="badge badge-danger">Vencido</span>
                                        @else
                                            <span class="badge badge-warning">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="2"><strong>Totales</strong></td>
                                <td><strong>${{ number_format($totalMatricula, 0, ',', '.') }}</strong></td>
                                <td class="text-success"><strong>${{ number_format($totalPagado, 0, ',', '.') }}</strong></td>
                                <td class="{{ $saldoTotal > 0 ? 'text-danger' : '' }}"><strong>${{ number_format($saldoTotal, 0, ',', '.') }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Evaluación de criterios del nivel</h3>
                    <div class="card-tools">
                        @if ($matricula->resultado_final === 'aprobado')
                            <span class="badge badge-success">Nivel aprobado</span>
                        @elseif ($matricula->resultado_final === 'reprobado')
                            <span class="badge badge-danger">Nivel reprobado</span>
                        @else
                            <span class="badge badge-warning">En curso</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th>Criterio</th>
                                <th width="180">Estado</th>
                                <th width="150">Fecha evaluación</th>
                                <th width="140">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($matricula->evaluaciones as $evaluacion)
                                <tr>
                                    <td>{{ $evaluacion->criterio->orden ?? '—' }}</td>
                                    <td>{{ $evaluacion->criterio->nombre ?? '—' }}</td>
                                    <td>
                                        <form action="{{ route('admin.evaluaciones.update', $evaluacion->id) }}" method="POST" class="form-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="estado_criterio" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="no_logrado" {{ $evaluacion->estado_criterio == 'no_logrado' ? 'selected' : '' }}>No logrado</option>
                                                <option value="en_proceso" {{ $evaluacion->estado_criterio == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                                                <option value="logrado" {{ $evaluacion->estado_criterio == 'logrado' ? 'selected' : '' }}>Logrado</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        {{ $evaluacion->fecha_evaluacion ? \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') : '—' }}
                                    </td>
                                    <td>
                                        @if ($evaluacion->estado_criterio === 'logrado')
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Logrado</span>
                                        @elseif ($evaluacion->estado_criterio === 'en_proceso')
                                            <span class="badge badge-info">En proceso</span>
                                        @else
                                            <span class="badge badge-secondary">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Este nivel no tiene criterios de evaluación configurados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.matriculas.resultado', $matricula->id) }}" method="POST" class="form-inline">
                        @csrf
                        @method('PUT')
                        <label class="mr-2 mb-0">Resultado final del nivel:</label>
                        <select name="resultado_final" class="form-control form-control-sm mr-2">
                            <option value="en_curso" {{ $matricula->resultado_final == 'en_curso' ? 'selected' : '' }}>En curso</option>
                            <option value="aprobado" {{ $matricula->resultado_final == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="reprobado" {{ $matricula->resultado_final == 'reprobado' ? 'selected' : '' }}>Reprobado</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save"></i> Guardar resultado
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.matriculas.index') }}" class="btn btn-secondary">Volver</a>

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

    @if (session('error'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se puede actualizar',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

@stop
