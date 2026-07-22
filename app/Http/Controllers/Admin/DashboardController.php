<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\Grupo;
use App\Models\Cobro;
use App\Models\Pago;
use App\Models\Noticia;
use Carbon\Carbon;

class DashboardController extends Controller
{
  public function index()
  {
    // ── Fila 1 — Números clave ──────────────────────────────
    $alumnosActivos = Alumno::where('estado', 'activo')->count();
    $docentesActivos = Docente::where('estado', 'ACTIVO')->count();
    $matriculasActivas = Matricula::where('estado', 'activa')->count();
    $gruposActivos = Grupo::where('estado', 'activo')->count();

    // ── Fila 2 — Financiero ──────────────────────────────────
    $cobrosVencidos = Cobro::with('matricula.alumno', 'matricula.grupo.nivel')
      ->where('estado', 'vencido')
      ->get();
    $carteraMorosaTotal = $cobrosVencidos->sum(fn($c) => $c->saldo_pendiente);
    $carteraMorosaCantidad = $cobrosVencidos->count();

    $recaudadoEsteMes = Pago::whereMonth('fecha_pago', now()->month)
      ->whereYear('fecha_pago', now()->year)
      ->where('metodo_pago', '!=', 'nota_debito')
      ->sum('valor_pagado');

    $cobrosPorVencer = Cobro::whereIn('estado', ['pendiente', 'parcial'])
      ->whereBetween('fecha_vencimiento', [now()->toDateString(), now()->addDays(7)->toDateString()])
      ->get();
    $porVencerCantidad = $cobrosPorVencer->count();
    $porVencerTotal = $cobrosPorVencer->sum(fn($c) => $c->saldo_pendiente);

    // ── Fila 3 — Cartera morosa detallada (top 10 por alumno) ──
    $carteraMorosaPorAlumno = $cobrosVencidos
      ->groupBy(fn($c) => $c->matricula->alumno_id ?? 0)
      ->map(function ($cobros) {
        $primero = $cobros->first();
        return [
          'alumno'         => $primero->matricula->alumno ?? null,
          'grupo'          => $primero->matricula->grupo ?? null,
          'saldoTotal'     => $cobros->sum(fn($c) => $c->saldo_pendiente),
          'cuotasVencidas' => $cobros->count(),
          'diasAtraso'     => $cobros->min(fn($c) => Carbon::parse($c->fecha_vencimiento)->diffInDays(now())),
        ];
      })
      ->sortByDesc('saldoTotal')
      ->take(10)
      ->values();

    // ── Fila 4 — Extras ──────────────────────────────────────
    $alumnosPorNivel = Matricula::where('estado', 'activa')
      ->with('grupo.nivel')
      ->get()
      ->groupBy(fn($m) => $m->grupo->nivel->nombre ?? 'Sin nivel')
      ->map->count()
      ->sortDesc();
    $maxAlumnosPorNivel = $alumnosPorNivel->max() ?: 1;

    $ultimasMatriculas = Matricula::with('alumno', 'grupo.nivel')
      ->latest('fecha_matricula')
      ->take(5)
      ->get();

    $ultimasNoticias = Noticia::where('estado', 'ACTIVO')
      ->orderBy('fecha_publicacion', 'desc')
      ->take(5)
      ->get();

    return view('admin.dashboard', compact(
      'alumnosActivos',
      'docentesActivos',
      'matriculasActivas',
      'gruposActivos',
      'carteraMorosaTotal',
      'carteraMorosaCantidad',
      'recaudadoEsteMes',
      'porVencerCantidad',
      'porVencerTotal',
      'carteraMorosaPorAlumno',
      'alumnosPorNivel',
      'maxAlumnosPorNivel',
      'ultimasMatriculas',
      'ultimasNoticias'
    ));
  }
}
