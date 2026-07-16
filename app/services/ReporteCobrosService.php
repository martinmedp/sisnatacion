<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Cobro;
use App\Models\Matricula;

class ReporteCobrosService
{
    /**
     * Consolida los cobros de cada matrícula en un solo registro:
     * progreso de cuotas (ej. "2/6"), próxima cuota pendiente a pagar,
     * saldo total y estado general. Aplica filtros por alumno/código,
     * estado, nivel, grupo y orden. Usado tanto por la vista operativa
     * de Cobros como por el Reporte de Cartera en el menú Reportes.
     */
    public static function filtrar(Request $request): array
    {
        $buscar  = $request->get('buscar');
        $estado  = $request->get('estado');
        $nivelId = $request->get('nivel_id');
        $grupoId = $request->get('grupo_id');
        $orden   = $request->get('orden', 'vencimiento');

        // Actualiza automáticamente a 'vencido' las cuotas pendientes/parciales cuya fecha ya pasó
        Cobro::whereIn('estado', ['pendiente', 'parcial'])
            ->where('fecha_vencimiento', '<', now()->toDateString())
            ->get()
            ->each(function ($cobro) {
                if ($cobro->valor_pagado <= 0) {
                    $cobro->update(['estado' => 'vencido']);
                }
            });

        $matriculas = Matricula::with([
                'alumno', 'grupo.nivel', 'grupo.sede',
                'cobros' => fn ($q) => $q->orderBy('numero_cuota'),
                'cobros.pagos',
            ])
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('alumno', function ($q) use ($buscar) {
                    $q->where('nombre_completo', 'like', "%{$buscar}%")
                      ->orWhere('codigo', 'like', "%{$buscar}%");
                });
            })
            ->when($grupoId, function ($query) use ($grupoId) {
                $query->where('grupo_id', $grupoId);
            })
            ->when(!$grupoId && $nivelId, function ($query) use ($nivelId) {
                $query->whereHas('grupo', function ($gq) use ($nivelId) {
                    $gq->where('nivel_id', $nivelId);
                });
            })
            ->whereHas('cobros')
            ->get();

        $registros = $matriculas->map(function ($matricula) {
            $totalCuotas = $matricula->numero_cuotas;
            $cuotasPagadas = $matricula->cobros->where('estado', 'pagado')->count();
            $proximoCobro = $matricula->cobros->firstWhere('estado', '!=', 'pagado');
            $saldoPendienteTotal = $matricula->cobros->sum(fn ($c) => $c->saldo_pendiente);

            if ($cuotasPagadas >= $totalCuotas) {
                $estadoGeneral = 'pagado';
            } elseif ($proximoCobro && $proximoCobro->estado === 'vencido') {
                $estadoGeneral = 'vencido';
            } elseif ($proximoCobro && $proximoCobro->estado === 'parcial') {
                $estadoGeneral = 'parcial';
            } else {
                $estadoGeneral = 'pendiente';
            }

            return [
                'matricula'           => $matricula,
                'totalCuotas'         => $totalCuotas,
                'cuotasPagadas'       => $cuotasPagadas,
                'progreso'            => $cuotasPagadas . '/' . $totalCuotas,
                'proximoCobro'        => $proximoCobro,
                'saldoPendienteTotal' => $saldoPendienteTotal,
                'estadoGeneral'       => $estadoGeneral,
                'todosLosPagos'       => $matricula->cobros->flatMap(fn ($c) => $c->pagos)->sortByDesc('fecha_pago')->values(),
            ];
        });

        if ($estado) {
            $registros = $registros->filter(fn ($r) => $r['estadoGeneral'] === $estado)->values();
        }

        $registros = match ($orden) {
            'alumno'  => $registros->sortBy(fn ($r) => $r['matricula']->alumno->nombre_completo ?? '')->values(),
            'codigo'  => $registros->sortBy(fn ($r) => $r['matricula']->alumno->codigo ?? 'zzz')->values(),
            default   => $registros->sortBy(fn ($r) => $r['proximoCobro']->fecha_vencimiento ?? '9999-12-31')->values(),
        };

        return compact('registros', 'buscar', 'estado', 'nivelId', 'grupoId', 'orden');
    }
}
