<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Matricula;

class AsistenciaController extends Controller
{
    /**
     * Consulta de asistencia para el administrador: elige un grupo
     * (de cualquier docente) y una fecha, y ve quién asistió, faltó,
     * llegó tarde o presentó excusa. Es de solo consulta — quien
     * marca la asistencia es el docente desde su propio panel.
     */
    public function index(Request $request)
    {
        $grupos = Grupo::with('nivel', 'sede', 'docente')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $grupoId = $request->get('grupo_id');
        $fecha = $request->get('fecha', now()->toDateString());

        $grupo = null;
        $matriculas = collect();

        if ($grupoId) {
            $grupo = Grupo::with('nivel', 'sede', 'docente')->findOrFail($grupoId);

            $matriculas = Matricula::where('grupo_id', $grupoId)
                ->where('estado', 'activa')
                ->with(['alumno', 'asistencias' => fn ($q) => $q->where('fecha', $fecha)])
                ->get()
                ->sortBy(fn ($m) => $m->alumno->nombre_completo ?? '');
        }

        return view('admin.asistencia.index', compact('grupos', 'grupoId', 'fecha', 'grupo', 'matriculas'));
    }
}
