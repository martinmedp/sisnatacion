<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Cobros</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        /* ===== ENCABEZADO ===== */
        .header {
            display: table;
            width: 90%;
            border-bottom: 2px solid #005F8F;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .header-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }

        .header-info h1 {
            font-size: 16px;
            color: #005F8F;
            margin-bottom: 2px;
        }

        .header-info p {
            font-size: 9px;
            color: #050505;
        }

        .header-fecha {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 9px;
            color: #050505;
        }

        /* ===== TÍTULO DEL REPORTE ===== */
        .titulo-reporte {
            margin-bottom: 12px;
        }

        .titulo-reporte h2 {
            font-size: 13px;
            color: #333;
            margin-bottom: 3px;
        }

        .titulo-reporte p {
            font-size: 9px;
            color: #777;
        }

        /* ===== TABLA ===== */
        table.datos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.datos th {
            background-color: #005F8F;
            color: #fff;
            font-size: 11px;
            text-align: left;
            padding: 6px 5px;
        }

        table.datos td {
            font-size: 9px;
            padding: 5px;
            border-bottom: 1px solid #e0e0e0;
        }

        table.datos tr:nth-child(even) {
            background-color: #f7f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            color: #fff;
        }

        .badge-pendiente {
            background-color: #f0ad4e;
        }

        .badge-parcial {
            background-color: #5bc0de;
        }

        .badge-pagado {
            background-color: #5cb85c;
        }

        .badge-vencido {
            background-color: #d9534f;
        }

        /* ===== TOTALES ===== */
        .totales {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .totales td {
            font-size: 10px;
            padding: 6px 5px;
            font-weight: bold;
        }

        .totales .label {
            text-align: right;
            color: #666;
        }

        .totales .valor {
            text-align: right;
            width: 90px;
        }

        /* ===== PIE DE PÁGINA ===== */
        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #0d0d0d;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="header-logo">
            @if ($configuracion && $configuracion->logo)
                <img src="{{ public_path($configuracion->logo) }}" alt="Logo">
            @endif
        </div>
        <div class="header-info text-center">
            <h1>{{ $configuracion->nombre ?? 'SisNatación' }}</h1>
            <p>Reporte de cobros y cuotas</p>
        </div>
        <div class="header-fecha">
            Generado: {{ now()->format('d/m/Y g:i a') }}
        </div>
    </div>

    {{-- TÍTULO Y FILTRO APLICADO --}}
    <div class="titulo-reporte">
        <h2>Listado de cuotas</h2>
        <p>Total de matrículas: {{ $registros->count() }}</p>
    </div>

    {{-- TABLA DE COBROS CONSOLIDADA POR MATRÍCULA --}}
    <table class="datos">
        <thead>
            <tr>
                <th class="text-center">Alumno</th>
                <th class="text-center">Código</th>
                <th class="text-center">Nivel / Grupo</th>
                <th class="text-center">Cuotas</th>
                <th class="text-center">Próx. vencimiento</th>
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
                    <td>{{ $matricula->alumno->codigo ?? '—' }}</td>
                    <td>
                        {{ $matricula->grupo->nivel->nombre ?? '—' }}
                        / {{ $matricula->grupo->nombre ?? '' }}
                    </td>
                    <td class="text-center">{{ $item['progreso'] }}</td>
                    <td class="text-center">
                        {{ $proximo ? \Carbon\Carbon::parse($proximo->fecha_vencimiento)->format('d/m/Y') : 'Al día' }}
                    </td>
                    <td class="text-right">${{ number_format($matricula->valor_cuota, 0, ',', '.') }}</td>
                    <td class="text-right">${{ number_format($item['saldoPendienteTotal'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item['estadoGeneral'] }}">
                            @switch($item['estadoGeneral'])
                                @case('pendiente')
                                    Pendiente
                                @break

                                @case('parcial')
                                    Parcial
                                @break

                                @case('pagado')
                                    Pagado
                                @break

                                @case('vencido')
                                    Vencido
                                @break
                            @endswitch
                        </span>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay registros con los filtros aplicados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- TOTALES --}}
        <table class="totales">
            <tr>
                <td class="label" style="width: 70%;">Total valor cuotas:</td>
                <td class="valor">${{ number_format($totalValor, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total pagado:</td>
                <td class="valor" style="color: #5cb85c;">${{ number_format($totalPagado, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total saldo pendiente:</td>
                <td class="valor" style="color: #d9534f;">${{ number_format($totalSaldo, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- PIE DE PÁGINA --}}
        <div class="footer">
            {{ $configuracion->nombre ?? 'SisNatación' }}
            @if ($configuracion && $configuracion->direccion)
                — {{ $configuracion->direccion }}
            @endif
            @if ($configuracion && $configuracion->telefono)
                — Tel: {{ $configuracion->telefono }}
            @endif
            @if ($configuracion && $configuracion->correo)
                — {{ $configuracion->correo }}
            @endif
        </div>

    </body>

    </html>
