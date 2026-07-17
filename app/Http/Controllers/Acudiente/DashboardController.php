<?php

namespace App\Http\Controllers\Acudiente;

use App\Http\Controllers\Controller;
use App\Models\Acudiente;

class DashboardController extends Controller
{
    public function index()
    {
        $acudiente = Acudiente::where('user_id', auth()->id())->first();

        $alumnos = collect();
        if ($acudiente) {
            $alumnos = $acudiente->alumnos()
                ->with(['matriculas' => function ($q) {
                    $q->where('estado', 'activa')->with('grupo.nivel', 'cobros')->latest('fecha_matricula');
                }])
                ->orderBy('nombre_completo')
                ->get();
        }

        return view('panel-acudiente.dashboard', compact('acudiente', 'alumnos'));
    }

    /**
     * Muestra el avance académico (niveles cursados y sus criterios)
     * de uno de los alumnos a cargo de este acudiente. Verifica que
     * el alumno realmente pertenezca a este acudiente antes de mostrar
     * nada.
     */
    public function avance($alumnoId)
    {
        $acudiente = Acudiente::where('user_id', auth()->id())->first();

        abort_if(!$acudiente, 403);

        $alumno = $acudiente->alumnos()->where('id', $alumnoId)->first();

        abort_if(!$alumno, 403, 'Este alumno no está a tu cargo.');

        $matriculas = $alumno->matriculas()
            ->with(['grupo.nivel', 'evaluaciones.criterio'])
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        return view('panel-acudiente.avance', compact('acudiente', 'alumno', 'matriculas'));
    }
}
