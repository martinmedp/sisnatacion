@extends('adminlte::page')

@section('title', 'Reporte de Cartera')

@section('content_header')
    <h1>Reporte de Cartera (Cobros)</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros del reporte</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reportes.cobros') }}" method="GET" class="form-inline mb-3">
                <input type="text" name="buscar" value="{{ $buscar }}"
                    class="form-control mr-2" style="width: 240px;"
                    placeholder="Buscar por nombre o código...">

                <select name="estado" class="form-control mr-2">
                    <option value="">-- Todos los estados --</option>
                    <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="parcial" {{ $estado == 'parcial' ? 'selected' : '' }}>Pago parcial</option>
                    <option value="pagado" {{ $estado == 'pagado' ? 'selected' : '' }}>Pagado (al día)</option>
                    <option value="vencido" {{ $estado == 'vencido' ? 'selected' : '' }}>Vencido</option>
                </select>

                <select name="nivel_id" class="form-control mr-2">
                    <option value="">-- Todos los niveles --</option>
                    @foreach ($niveles as $nivel)
                        <option value="{{ $nivel->id }}" {{ $nivelId == $nivel->id ? 'selected' : '' }}>
                            {{ $nivel->nombre }}
                        </option>
                    @endforeach
                </select>

                <select name="grupo_id" class="form-control mr-2">
                    <option value="">-- Todos los grupos --</option>
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->id }}" {{ $grupoId == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nombre }} ({{ $grupo->nivel->nombre ?? '' }})
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Consultar
                </button>
                @if ($buscar || $estado || $nivelId || $grupoId)
                    <a href="{{ route('admin.reportes.cobros') }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
                <a href="{{ route('admin.reportes.cobros.pdf', request()->only(['buscar', 'estado', 'nivel_id', 'grupo_id'])) }}"
                    target="_blank" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </a>
            </form>

            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i>
                Esta pantalla es solo de consulta. Para registrar pagos o notas débito, ve al módulo de <strong>Cobros</strong>.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Vista previa ({{ $registros->count() }} matrícula(s))</h3>
        </div>
        <div class="card-body">
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
                                <span class="badge badge-secondary">{{ $item['progreso'] }}</span>
                            </td>
                            <td class="text-center">
                                @if ($proximo)
                                    {{ \Carbon\Carbon::parse($proximo->fecha_vencimiento)->format('d/m/Y') }}
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay resultados con los filtros aplicados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop
