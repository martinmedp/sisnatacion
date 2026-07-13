@extends('adminlte::page')

@section('title', 'Cobros')

@section('content_header')
    <h1>Cobros</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de cuotas</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cobros.index') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="buscar" value="{{ $buscar }}"
                    class="form-control mr-2" style="width: 280px;"
                    placeholder="Buscar por nombre o código...">

                <select name="estado" class="form-control mr-2">
                    <option value="">-- Todos los estados --</option>
                    <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="parcial" {{ $estado == 'parcial' ? 'selected' : '' }}>Pago parcial</option>
                    <option value="pagado" {{ $estado == 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="vencido" {{ $estado == 'vencido' ? 'selected' : '' }}>Vencido</option>
                </select>

                <select name="orden" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="vencimiento" {{ $orden == 'vencimiento' ? 'selected' : '' }}>Ordenar por vencimiento</option>
                    <option value="alumno" {{ $orden == 'alumno' ? 'selected' : '' }}>Ordenar por alumno</option>
                    <option value="codigo" {{ $orden == 'codigo' ? 'selected' : '' }}>Ordenar por código</option>
                </select>

                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                @if ($buscar || $estado || $orden != 'vencimiento')
                    <a href="{{ route('admin.cobros.index') }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
                <a href="{{ route('admin.cobros.pdf', request()->only(['buscar', 'estado', 'orden'])) }}"
                    target="_blank" class="btn btn-outline-dark" title="Imprimir / Generar PDF">
                    <i class="fas fa-print"></i>
                </a>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Código</th>
                        <th>Nivel / Grupo</th>
                        <th>Cuota</th>
                        <th>Vencimiento</th>
                        <th>Valor</th>
                        <th>Pagado</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                        <th width="160">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cobros as $cobro)
                        <tr>
                            <td>{{ $cobro->matricula->alumno->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($cobro->matricula->alumno->codigo ?? null)
                                    <span class="badge badge-primary">{{ $cobro->matricula->alumno->codigo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $cobro->matricula->grupo->nivel->nombre ?? '—' }}
                                <br><small class="text-muted">{{ $cobro->matricula->grupo->nombre ?? '' }}</small>
                            </td>
                            <td>{{ $cobro->numero_cuota }} / {{ $cobro->matricula->numero_cuotas }}</td>
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
                            <td>
                                @if ($cobro->estado !== 'pagado')
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="registrarPago({{ $cobro->id }}, {{ $cobro->numero_cuota }}, {{ $cobro->saldo_pendiente }})">
                                        <i class="fas fa-dollar-sign"></i> Pagar
                                    </button>
                                @endif
                                @if ($cobro->pagos->count() > 0)
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-toggle="collapse" data-target="#historial-{{ $cobro->id }}">
                                        <i class="fas fa-history"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @if ($cobro->pagos->count() > 0)
                            <tr class="collapse" id="historial-{{ $cobro->id }}">
                                <td colspan="10" class="bg-light">
                                    <strong>Historial de pagos:</strong>
                                    <table class="table table-sm mb-0 mt-2">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Valor</th>
                                                <th>Método</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cobro->pagos as $pago)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                                    <td>${{ number_format($pago->valor_pagado, 0, ',', '.') }}</td>
                                                    <td>{{ ucfirst($pago->metodo_pago) }}</td>
                                                    <td>{{ $pago->observaciones ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                @if ($buscar || $estado)
                                    No se encontraron cobros con esos filtros.
                                @else
                                    No hay cobros generados. Se crean automáticamente al registrar una matrícula.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Formulario oculto para registrar el pago --}}
    <form id="form-pagar" action="" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="valor_pagado" id="input-valor-pagado">
        <input type="hidden" name="fecha_pago" id="input-fecha-pago">
        <input type="hidden" name="metodo_pago" id="input-metodo-pago">
        <input type="hidden" name="observaciones" id="input-observaciones">
    </form>

    <script>
        function registrarPago(cobroId, numeroCuota, saldoPendiente) {
            Swal.fire({
                title: 'Registrar pago — cuota #' + numeroCuota,
                html: `
                    <label style="display:block; text-align:left; margin-bottom:4px; font-size:13px;">Valor a pagar</label>
                    <input id="swal-valor" type="number" step="0.01" min="0.01" max="${saldoPendiente}"
                        class="swal2-input" style="width: 80%;" value="${saldoPendiente}">

                    <label style="display:block; text-align:left; margin-bottom:4px; font-size:13px;">Fecha de pago</label>
                    <input id="swal-fecha" type="date" class="swal2-input" style="width: 80%;"
                        value="${new Date().toISOString().split('T')[0]}">

                    <label style="display:block; text-align:left; margin-bottom:4px; font-size:13px;">Método de pago</label>
                    <select id="swal-metodo" class="swal2-input" style="width: 80%;">
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="otro">Otro</option>
                    </select>

                    <label style="display:block; text-align:left; margin-bottom:4px; font-size:13px;">Observaciones (opcional)</label>
                    <input id="swal-obs" type="text" class="swal2-input" style="width: 80%;">
                `,
                showCancelButton: true,
                confirmButtonText: 'Registrar pago',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const valor = document.getElementById('swal-valor').value;
                    if (!valor || valor <= 0) {
                        Swal.showValidationMessage('Ingresa un valor válido');
                        return false;
                    }
                    if (parseFloat(valor) > parseFloat(saldoPendiente)) {
                        Swal.showValidationMessage('El valor no puede superar el saldo pendiente ($' + saldoPendiente + ')');
                        return false;
                    }
                    return {
                        valor: valor,
                        fecha: document.getElementById('swal-fecha').value,
                        metodo: document.getElementById('swal-metodo').value,
                        obs: document.getElementById('swal-obs').value,
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-pagar');
                    form.action = '{{ url("admin/cobros") }}/' + cobroId + '/pagar';
                    document.getElementById('input-valor-pagado').value = result.value.valor;
                    document.getElementById('input-fecha-pago').value = result.value.fecha;
                    document.getElementById('input-metodo-pago').value = result.value.metodo;
                    document.getElementById('input-observaciones').value = result.value.obs;
                    form.submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $errors->first() }}',
                });
            });
        </script>
    @endif

@stop
