@extends('adminlte::page')

@section('title', 'Cobros')

@section('content_header')
    <h1>Cobros</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de matrículas y cuotas</h3>
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
                    <option value="pagado" {{ $estado == 'pagado' ? 'selected' : '' }}>Pagado (al día)</option>
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
                        <th class="text-center">Cuotas</th>
                        <th class="text-center">Próximo vencimiento</th>
                        <th class="text-right">Valor cuota</th>
                        <th class="text-right">Saldo total</th>
                        <th class="text-center">Estado</th>
                        <th width="180">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registros as $item)
                        @php
                            $matricula = $item['matricula'];
                            $proximo = $item['proximoCobro'];
                        @endphp
                        <tr>
                            <td>{{ $matricula->alumno->nombre_completo ?? '—' }}</td>
                            <td>
                                @if ($matricula->alumno->codigo ?? null)
                                    <span class="badge badge-primary">{{ $matricula->alumno->codigo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $matricula->grupo->nivel->nombre ?? '—' }}
                                <br><small class="text-muted">{{ $matricula->grupo->nombre ?? '' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary" style="font-size:12px;">{{ $item['progreso'] }}</span>
                            </td>
                            <td class="text-center">
                                @if ($proximo)
                                    {{ \Carbon\Carbon::parse($proximo->fecha_vencimiento)->format('d/m/Y') }}
                                    <br><small class="text-muted">cuota #{{ $proximo->numero_cuota }}</small>
                                @else
                                    <span class="text-success">Al día</span>
                                @endif
                            </td>
                            <td class="text-right">${{ number_format($matricula->valor_cuota, 0, ',', '.') }}</td>
                            <td class="text-right {{ $item['saldoPendienteTotal'] > 0 ? 'text-danger' : '' }}">
                                ${{ number_format($item['saldoPendienteTotal'], 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if ($item['estadoGeneral'] === 'pagado')
                                    <span class="badge badge-success">Pagado</span>
                                @elseif ($item['estadoGeneral'] === 'parcial')
                                    <span class="badge badge-info">Parcial</span>
                                @elseif ($item['estadoGeneral'] === 'vencido')
                                    <span class="badge badge-danger">Vencido</span>
                                @else
                                    <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @if ($proximo)
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="registrarPago({{ $proximo->id }}, {{ $proximo->numero_cuota }}, {{ $proximo->saldo_pendiente }})">
                                        <i class="fas fa-dollar-sign"></i> Pagar
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm"
                                        onclick="registrarNotaDebito({{ $proximo->id }}, {{ $proximo->numero_cuota }}, {{ $proximo->saldo_pendiente }})">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                @endif
                                @if ($item['todosLosPagos']->count() > 0)
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-toggle="collapse" data-target="#historial-{{ $matricula->id }}">
                                        <i class="fas fa-history"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @if ($item['todosLosPagos']->count() > 0)
                            <tr class="collapse" id="historial-{{ $matricula->id }}">
                                <td colspan="9" class="bg-light">
                                    <strong>Historial de pagos — {{ $matricula->alumno->nombre_completo ?? '' }}:</strong>
                                    <table class="table table-sm mb-0 mt-2">
                                        <thead>
                                            <tr>
                                                <th>Cuota</th>
                                                <th>Fecha</th>
                                                <th>Valor</th>
                                                <th>Método</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item['todosLosPagos'] as $pago)
                                                <tr>
                                                    <td>#{{ $pago->cobro->numero_cuota ?? '—' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                                    <td>${{ number_format($pago->valor_pagado, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($pago->metodo_pago === 'nota_debito')
                                                            <span class="badge badge-warning">Nota débito</span>
                                                        @else
                                                            {{ ucfirst($pago->metodo_pago) }}
                                                        @endif
                                                    </td>
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
                            <td colspan="9" class="text-center">
                                @if ($buscar || $estado)
                                    No se encontraron matrículas con esos filtros.
                                @else
                                    No hay matrículas con cuotas generadas.
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

    {{-- Formulario oculto para registrar la nota débito --}}
    <form id="form-nota-debito" action="" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="fecha_pago" id="nd-fecha-pago">
        <input type="hidden" name="observaciones" id="nd-observaciones">
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

    <script>
        function registrarNotaDebito(cobroId, numeroCuota, saldoPendiente) {
            Swal.fire({
                title: 'Nota débito — cuota #' + numeroCuota,
                html: `
                    <p style="text-align:left; font-size:13px; color:#856404; background:#fff3cd; padding:8px; border-radius:4px;">
                        Se condonará el saldo pendiente de <strong>$${Number(saldoPendiente).toLocaleString('es-CO')}</strong> de esta cuota.
                        Esta acción marca la cuota como pagada sin recibir dinero real.
                    </p>

                    <label style="display:block; text-align:left; margin-bottom:4px; font-size:13px;">Fecha</label>
                    <input id="swal-nd-fecha" type="date" class="swal2-input" style="width: 80%;"
                        value="${new Date().toISOString().split('T')[0]}">

                    <label style="display:block; text-align:left; margin-bottom:4px; font-size:13px;">Motivo de la nota débito (obligatorio)</label>
                    <textarea id="swal-nd-obs" class="swal2-textarea" style="width: 80%;" placeholder="Ej. Acuerdo con el acudiente por retiro anticipado"></textarea>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar nota débito',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ffc107',
                preConfirm: () => {
                    const obs = document.getElementById('swal-nd-obs').value.trim();
                    if (!obs) {
                        Swal.showValidationMessage('Debes indicar el motivo');
                        return false;
                    }
                    return {
                        fecha: document.getElementById('swal-nd-fecha').value,
                        obs: obs,
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-nota-debito');
                    form.action = '{{ url("admin/cobros") }}/' + cobroId + '/nota-debito';
                    document.getElementById('nd-fecha-pago').value = result.value.fecha;
                    document.getElementById('nd-observaciones').value = result.value.obs;
                    form.submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({
                    icon: 'success', title: 'Correcto', text: '{{ session('success') }}',
                    timer: 2000, timerProgressBar: true, showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            window.addEventListener('load', function () {
                Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}' });
            });
        </script>
    @endif

@stop
