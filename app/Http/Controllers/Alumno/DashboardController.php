<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Alumno;

class DashboardController extends Controller
{
    public function index()
    {
        $alumno = Alumno::with('acudiente')->where('user_id', auth()->id())->first();

        $matriculaActiva = null;
        $proximaCuota = null;

        if ($alumno) {
            $matriculaActiva = $alumno->matriculas()
                ->with('grupo.nivel', 'grupo.sede', 'cobros')
                ->where('estado', 'activa')
                ->latest('fecha_matricula')
                ->first();

            if ($matriculaActiva) {
                $proximaCuota = $matriculaActiva->cobros
                    ->sortBy('numero_cuota')
                    ->first(fn ($c) => $c->estado !== 'pagado');
            }
        }

        return view('panel-alumno.dashboard', compact('alumno', 'matriculaActiva', 'proximaCuota'));
    }

    /**
     * Muestra el historial de niveles cursados por el alumno con
     * el detalle de criterios logrados/en proceso/no logrados de
     * cada uno (avance académico).
     */
    public function avance()
    {
        $alumno = Alumno::where('user_id', auth()->id())->first();

        $matriculas = collect();
        if ($alumno) {
            $matriculas = $alumno->matriculas()
                ->with(['grupo.nivel', 'evaluaciones.criterio'])
                ->orderBy('fecha_matricula', 'desc')
                ->get();
        }

        return view('panel-alumno.avance', compact('alumno', 'matriculas'));
    }

    /**
     * Muestra el libro observador del alumno (solo lectura) —
     * anotaciones de comportamiento, conducta y rendimiento que
     * han registrado sus docentes.
     */
    public function observador()
    {
        $alumno = Alumno::with('observador.docente')->where('user_id', auth()->id())->first();

        return view('panel-alumno.observador', compact('alumno'));
    }
}
