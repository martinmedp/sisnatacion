<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cobro;
use App\Models\Configuracion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CobroController extends Controller
{
    /**
     * Aplica los mismos filtros (buscar, estado, orden) usados tanto
     * en la vista como en la exportación a PDF, para mantenerlos idénticos.
     */
    private function obtenerCobrosFiltrados(Request $request)
    {
        $buscar = $request->get('buscar');
        $estado = $request->get('estado');
        $orden  = $request->get('orden', 'vencimiento');

        $cobros = Cobro::with(['matricula.alumno', 'matricula.grupo.nivel', 'matricula.grupo.sede', 'pagos'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('matricula.alumno', function ($q) use ($buscar) {
                    $q->where('nombre_completo', 'like', "%{$buscar}%")
                      ->orWhere('codigo', 'like', "%{$buscar}%");
                });
            })
            ->when($estado, function ($query) use ($estado) {
                $query->where('estado', $estado);
            })
            ->get();

        $cobros = match ($orden) {
            'alumno'  => $cobros->sortBy(fn ($c) => $c->matricula->alumno->nombre_completo ?? '')->values(),
            'codigo'  => $cobros->sortBy(fn ($c) => $c->matricula->alumno->codigo ?? 'zzz')->values(),
            default   => $cobros->sortBy('fecha_vencimiento')->values(),
        };

        return [$cobros, $buscar, $estado, $orden];
    }

    public function index(Request $request)
    {
        // Actualiza automáticamente a 'vencido' las cuotas pendientes/parciales cuya fecha ya pasó
        Cobro::whereIn('estado', ['pendiente', 'parcial'])
            ->where('fecha_vencimiento', '<', now()->toDateString())
            ->get()
            ->each(function ($cobro) {
                if ($cobro->valor_pagado <= 0) {
                    $cobro->update(['estado' => 'vencido']);
                }
            });

        [$cobros, $buscar, $estado, $orden] = $this->obtenerCobrosFiltrados($request);

        return view('admin.cobros.index', compact('cobros', 'buscar', 'estado', 'orden'));
    }

    public function exportarPdf(Request $request)
    {
        [$cobros, $buscar, $estado, $orden] = $this->obtenerCobrosFiltrados($request);

        $configuracion = Configuracion::first();

        $totalValor   = $cobros->sum('valor');
        $totalPagado  = $cobros->sum('valor_pagado');
        $totalSaldo   = $cobros->sum('saldo_pendiente');

        $etiquetasEstado = [
            'pendiente' => 'Pendiente',
            'parcial'   => 'Pago parcial',
            'pagado'    => 'Pagado',
            'vencido'   => 'Vencido',
        ];

        $filtroDescripcion = collect([
            $buscar ? 'Búsqueda: "' . $buscar . '"' : null,
            $estado ? 'Estado: ' . ($etiquetasEstado[$estado] ?? $estado) : null,
        ])->filter()->implode(' — ') ?: 'Todos los cobros';

        $pdf = Pdf::loadView('admin.cobros.pdf', compact(
            'cobros', 'configuracion', 'filtroDescripcion',
            'totalValor', 'totalPagado', 'totalSaldo'
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
            ->with('success', 'Pago de $' . number_format($request->valor_pagado, 0, ',', '.') . ' registrado correctamente');
    }
}
