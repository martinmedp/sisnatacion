<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Grupo;

class DashboardController extends Controller
{
    public function index()
    {
        $docente = Docente::where('user_id', auth()->id())->first();

        $grupos = collect();
        if ($docente) {
            $grupos = Grupo::with(['nivel', 'sede', 'horarios'])
                ->where('docente_id', $docente->id)
                ->where('estado', 'activo')
                ->orderBy('nombre')
                ->get();
        }

        return view('panel-docente.dashboard', compact('docente', 'grupos'));
    }

    /**
     * Lista los alumnos matriculados y activos de uno de los grupos
     * de este docente, con accesos a asistencia, logros y observador.
     * Verifica que el grupo realmente pertenezca a este docente.
     */
    public function alumnos($grupoId)
    {
        $docente = Docente::where('user_id', auth()->id())->first();
        abort_if(!$docente, 403);

        $grupo = Grupo::with('nivel', 'sede')
            ->where('id', $grupoId)
            ->where('docente_id', $docente->id)
            ->first();

        abort_if(!$grupo, 403, 'Este grupo no está a tu cargo.');

        $matriculas = $grupo->matriculas()
            ->with('alumno')
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->alumno->nombre_completo ?? '');

        return view('panel-docente.alumnos', compact('docente', 'grupo', 'matriculas'));
    }
}
