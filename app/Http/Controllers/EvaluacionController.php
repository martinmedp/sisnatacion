<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;
use App\Models\Matricula;

class EvaluacionController extends Controller
{
    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        $request->validate([
            'estado_criterio' => 'required|in:no_logrado,en_proceso,logrado',
            'docente_id'      => 'nullable|exists:docentes,id',
            'observaciones'   => 'nullable|string|max:500',
        ]);

        $evaluacion->update([
            'estado_criterio' => $request->estado_criterio,
            'docente_id'      => $request->docente_id,
            'observaciones'   => $request->observaciones,
            'fecha_evaluacion' => now()->toDateString(),
        ]);

        return redirect()
            ->route('admin.matriculas.edit', $evaluacion->matricula_id)
            ->with('success', 'Evaluación actualizada correctamente');
    }

    /**
     * Marca el resultado final del nivel para una matrícula
     * (aprobado / reprobado), una vez evaluados todos los criterios.
     */
    public function actualizarResultado(Request $request, $matriculaId)
    {
        $request->validate([
            'resultado_final' => 'required|in:en_curso,aprobado,reprobado',
        ]);

        $matricula = Matricula::findOrFail($matriculaId);
        $matricula->update(['resultado_final' => $request->resultado_final]);

        return redirect()
            ->route('admin.matriculas.edit', $matricula->id)
            ->with('success', 'Resultado final actualizado correctamente');
    }
}
