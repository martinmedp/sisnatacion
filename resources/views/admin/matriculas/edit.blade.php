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

@stop