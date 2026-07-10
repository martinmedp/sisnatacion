<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cobro;
use Illuminate\Support\Facades\DB;

class CobroController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $estado = $request->get('estado');

        // Actualiza automáticamente a 'vencido' las cuotas pendientes cuya fecha ya pasó
        Cobro::whereIn('estado', ['pendiente', 'parcial'])
            ->where('fecha_vencimiento', '<', now()->toDateString())
            ->get()
            ->each(function ($cobro) {
                if ($cobro->valor_pagado <= 0) {
                    $cobro->update(['estado' => 'vencido']);
                }
            });

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
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        return view('admin.cobros.index', compact('cobros', 'buscar', 'estado'));
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
