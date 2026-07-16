<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $metodo = $request->get('metodo');

        $pagos = Pago::with(['cobro.matricula.alumno', 'cobro.matricula.grupo.nivel'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('cobro.matricula.alumno', function ($q) use ($buscar) {
                    $q->where('nombre_completo', 'like', "%{$buscar}%")
                      ->orWhere('codigo', 'like', "%{$buscar}%");
                });
            })
            ->when($metodo, function ($query) use ($metodo) {
                $query->where('metodo_pago', $metodo);
            })
            ->orderBy('fecha_pago', 'desc')
            ->get();

        $totalPagado = $pagos->where('metodo_pago', '!=', 'nota_debito')->sum('valor_pagado');
        $totalCondonado = $pagos->where('metodo_pago', 'nota_debito')->sum('valor_pagado');

        return view('admin.pagos.index', compact('pagos', 'buscar', 'metodo', 'totalPagado', 'totalCondonado'));
    }
}
