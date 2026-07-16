@extends('adminlte::page')

@section('title', 'Pagos')

@section('content_header')
    <h1>Pagos</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total recaudado</span>
                    <span class="info-box-number">${{ number_format($totalPagado, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total condonado (notas débito)</span>
                    <span class="info-box-number">${{ number_format($totalCondonado, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial de pagos y notas débito</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pagos.index') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="buscar" value="{{ $buscar }}"
                    class="form-control mr-2" style="width: 280px;"
                    placeholder="Buscar por nombre o código...">

                <select name="metodo" class="form-control mr-2">
                    <option value="">-- Todos los métodos --</option>
                    <option value="efectivo" {{ $metodo == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="transferencia" {{ $metodo == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="otro" {{ $metodo == 'otro' ? 'selected' : '' }}>Otro</option>
                    <option value="nota_debito" {{ $metodo == 'nota_debito' ? 'selected' : '' }}>Nota débito</option>
                </select>

                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                @if ($buscar || $metodo)
                    <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Alumno</th>
                        <th>Código</th>
                        <th>Nivel</th>
                        <th>Cuota</th>
                        <th>Valor</th>
                        <th>Método</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                            <td>{{ $pago->cobro->matricula->alumno->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($pago->cobro->matricula->alumno->codigo ?? null)
                                    <span class="badge badge-primary">{{ $pago->cobro->matricula->alumno->codigo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $pago->cobro->matricula->grupo->nivel->nombre ?? '—' }}</td>
                            <td>{{ $pago->cobro->numero_cuota ?? '—' }} / {{ $pago->cobro->matricula->numero_cuotas ?? '—' }}</td>
                            <td class="{{ $pago->metodo_pago === 'nota_debito' ? 'text-warning' : 'text-success' }}">
                                ${{ number_format($pago->valor_pagado, 0, ',', '.') }}
                            </td>
                            <td>
                                @if ($pago->metodo_pago === 'nota_debito')
                                    <span class="badge badge-warning">Nota débito</span>
                                @else
                                    {{ ucfirst($pago->metodo_pago) }}
                                @endif
                            </td>
                            <td>{{ $pago->observaciones ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                @if ($buscar || $metodo)
                                    No se encontraron pagos con esos filtros.
                                @else
                                    No hay pagos registrados todavía.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <small class="text-muted">
                Para registrar un nuevo pago o abono, ve al módulo de <strong>Cobros</strong> y usa el botón "Pagar" en la cuota correspondiente.
            </small>
        </div>
    </div>

@stop
