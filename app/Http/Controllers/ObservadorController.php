<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;

class ObservadorController extends Controller
{
    /**
     * Consulta del libro observador para el administrador: elige un
     * alumno (de cualquier grupo/docente) y ve todo su historial de
     * anotaciones. Es de solo consulta — quien escribe las
     * anotaciones es el docente desde su propio panel.
     */
    public function index(Request $request)
    {
        $alumnos = Alumno::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        $alumnoId = $request->get('alumno_id');
        $alumno = null;

        if ($alumnoId) {
            $alumno = Alumno::with('observador.docente')->findOrFail($alumnoId);
        }

        return view('admin.observador.index', compact('alumnos', 'alumnoId', 'alumno'));
    }
}
