<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\Evaluacion;
use Illuminate\Http\Request;

class LogroController extends Controller
{
    /**
     * Muestra los criterios de evaluación del nivel actual de un
     * alumno (según su matrícula activa), para que el docente los
     * vaya calificando. Verifica que la matrícula pertenezca a un
     * grupo de este docente.
     */
    public function index($matriculaId)
    {
        $docente = $this->docenteAutenticado();

        $matricula = Matricula::with(['alumno', 'grupo.nivel', 'evaluaciones.criterio'])
            ->where('id', $matriculaId)
            ->whereHas('grupo', fn ($q) => $q->where('docente_id', $docente->id))
            ->firstOrFail();

        return view('panel-docente.logros', compact('docente', 'matricula'));
    }

    public function update(Request $request, $evaluacionId)
    {
        $docente = $this->docenteAutenticado();

        $evaluacion = Evaluacion::with('matricula.grupo')->findOrFail($evaluacionId);

        // Verificar que esta evaluación pertenezca a un alumno de un grupo de este docente
        abort_if(
            !$evaluacion->matricula || $evaluacion->matricula->grupo->docente_id !== $docente->id,
            403,
            'No tienes permiso para calificar a este alumno.'
        );

        $request->validate([
            'estado_criterio' => 'required|in:no_logrado,en_proceso,logrado',
            'observaciones'   => 'nullable|string|max:500',
        ]);

        $evaluacion->update([
            'estado_criterio'  => $request->estado_criterio,
            'docente_id'       => $docente->id,
            'observaciones'    => $request->observaciones,
            'fecha_evaluacion' => now()->toDateString(),
        ]);

        return redirect()
            ->route('docente.logros.index', $evaluacion->matricula_id)
            ->with('success', 'Logro actualizado correctamente');
    }

    private function docenteAutenticado(): Docente
    {
        $docente = Docente::where('user_id', auth()->id())->first();
        abort_if(!$docente, 403);

        return $docente;
    }
}
