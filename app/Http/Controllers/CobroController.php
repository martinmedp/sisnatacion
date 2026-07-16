<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cobro;
use App\Models\Configuracion;
use App\Models\Nivel;
use App\Models\Grupo;
use App\Services\ReporteCobrosService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CobroController extends Controller
{
    public function index(Request $request)
    {
        $datos = ReporteCobrosService::filtrar($request);

        $niveles = Nivel::where('estado', 'activo')->orderBy('orden')->get();
        $grupos  = Grupo::with('nivel')->where('estado', 'activo')->orderBy('nombre')->get();

        return view('admin.cobros.index', array_merge($datos, compact('niveles', 'grupos')));
    }

    public function exportarPdf(Request $request)
    {
        $datos = ReporteCobrosService::filtrar($request);
        $registros = $datos['registros'];

        $configuracion = Configuracion::first();

        $totalValor  = $registros->sum(fn ($r) => $r['matricula']->valor_total_con_descuento);
        $totalPagado = $registros->sum(fn ($r) => $r['matricula']->cobros->sum(fn ($c) => $c->valor_pagado));
        $totalSaldo  = $registros->sum('saldoPendienteTotal');

        $etiquetasEstado = [
            'pendiente' => 'Pendiente', 'parcial' => 'Pago parcial',
            'pagado' => 'Pagado', 'vencido' => 'Vencido',
        ];

        $descripcionNivelGrupo = null;
        if ($datos['grupoId']) {
            $grupo = Grupo::find($datos['grupoId']);
            $descripcionNivelGrupo = 'Grupo: ' . ($grupo->nombre ?? '');
        } elseif ($datos['nivelId']) {
            $nivel = Nivel::find($datos['nivelId']);
            $descripcionNivelGrupo = 'Nivel: ' . ($nivel->nombre ?? '');
        }

        $filtroDescripcion = collect([
            $datos['buscar'] ? 'Búsqueda: "' . $datos['buscar'] . '"' : null,
            $datos['estado'] ? 'Estado: ' . ($etiquetasEstado[$datos['estado']] ?? $datos['estado']) : null,
            $descripcionNivelGrupo,
        ])->filter()->implode(' — ') ?: 'Todas las matrículas';

        $pdf = Pdf::loadView('admin.cobros.pdf', compact(
            'registros', 'configuracion', 'filtroDescripcion', 'totalValor', 'totalPagado', 'totalSaldo'
        ))->setPaper('letter', 'landscape');

        return $pdf->stream('reporte-cobros-' . now()->format('Y-m-d') . '.pdf');
    }

    public function registrarPago(Request $request, $id)
    {
        $cobro = Cobro::findOrFail($id);

        $request->validate([
            'valor_pagado' => 'required|numeric|min:0.01|max:' . $cobro->saldo_pendiente,
            'fecha_pago'   => 'required|date',
            'metodo_pago'  => 'required|in:efectivo,transferencia,otro',
            'observaciones' => 'nullable|string|max:255',
        ], [
            'valor_pagado.max' => 'El valor pagado no puede ser mayor al saldo pendiente ($' . number_format($cobro->saldo_pendiente, 0, ',', '.') . ').',
        ]);

        DB::transaction(function () use ($request, $cobro) {
            $cobro->pagos()->create([
                'valor_pagado'  => $request->valor_pagado,
                'fecha_pago'    => $request->fecha_pago,
                'metodo_pago'   => $request->metodo_pago,
                'observaciones' => $request->observaciones,
            ]);

            $cobro->actualizarEstado();
        });

        return redirect()
            ->route('admin.cobros.index')
            ->with('success', 'Pago de $' . number_format($request->valor_pagado, 0, ',', '.') . ' registrado correctamente (cuota #' . $cobro->numero_cuota . ')');
    }

    public function registrarNotaDebito(Request $request, $id)
    {
        $cobro = Cobro::findOrFail($id);

        if ($cobro->saldo_pendiente <= 0) {
            return redirect()
                ->route('admin.cobros.index')
                ->with('error', 'Esta cuota ya está pagada, no tiene saldo pendiente para condonar.');
        }

        $request->validate([
            'fecha_pago'    => 'required|date',
            'observaciones' => 'required|string|max:255',
        ], [
            'observaciones.required' => 'Debes indicar el motivo de la nota débito.',
        ]);

        DB::transaction(function () use ($request, $cobro) {
            $cobro->pagos()->create([
                'valor_pagado'  => $cobro->saldo_pendiente,
                'fecha_pago'    => $request->fecha_pago,
                'metodo_pago'   => 'nota_debito',
                'observaciones' => $request->observaciones,
            ]);

            $cobro->actualizarEstado();
        });

        return redirect()
            ->route('admin.cobros.index')
            ->with('success', 'Nota débito registrada — se condonó el saldo de la cuota #' . $cobro->numero_cuota);
    }
}
