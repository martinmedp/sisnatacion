<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;
use App\Models\Nivel;
use App\Models\Grupo;
use App\Services\ReporteCobrosService;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Página del Reporte de Cartera (Cobros) — vista de solo consulta,
     * sin acciones de pago, pensada para generar el PDF con los
     * filtros elegidos: alumno/código, estado, nivel o grupo.
     */
    public function cobros(Request $request)
    {
        $datos = ReporteCobrosService::filtrar($request);

        $niveles = Nivel::where('estado', 'activo')->orderBy('orden')->get();
        $grupos  = Grupo::with('nivel')->where('estado', 'activo')->orderBy('nombre')->get();

        return view('admin.reportes.cobros', array_merge($datos, compact('niveles', 'grupos')));
    }

    public function cobrosPdf(Request $request)
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

        // Reutiliza la misma plantilla PDF que usa la vista operativa de Cobros
        $pdf = Pdf::loadView('admin.cobros.pdf', compact(
            'registros', 'configuracion', 'filtroDescripcion', 'totalValor', 'totalPagado', 'totalSaldo'
        ))->setPaper('letter', 'landscape');

        return $pdf->stream('reporte-cartera-' . now()->format('Y-m-d') . '.pdf');
    }
}
