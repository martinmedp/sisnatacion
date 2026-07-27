<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;
use App\Models\HorarioAtencionSede;

class HorarioAtencionSedeController extends Controller
{
    private array $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

    /**
     * Muestra el formulario de configuración del horario de atención
     * de una sede: los 7 días de la semana (vacío = cerrado ese día)
     * y la duración de cada bloque de clase.
     */
    public function edit($sedeId)
    {
        $sede = Sede::findOrFail($sedeId);

        $atencionPorDia = $sede->horariosAtencion()->get()->keyBy('dia_semana');

        return view('admin.horarios-atencion.edit', [
            'sede'           => $sede,
            'dias'           => $this->dias,
            'atencionPorDia' => $atencionPorDia,
        ]);
    }

    public function update(Request $request, $sedeId)
    {
        $sede = Sede::findOrFail($sedeId);

        $request->validate([
            'duracion_clase_minutos' => 'required|integer|min:10|max:240',
            'hora_inicio'            => 'nullable|array',
            'hora_fin'               => 'nullable|array',
        ]);

        $sede->update([
            'duracion_clase_minutos' => $request->duracion_clase_minutos,
        ]);

        foreach ($this->dias as $dia) {
            $inicio = $request->input("hora_inicio.$dia");
            $fin = $request->input("hora_fin.$dia");

            if (empty($inicio) || empty($fin)) {
                // Día sin horario definido = cerrado ese día
                HorarioAtencionSede::where('sede_id', $sede->id)
                    ->where('dia_semana', $dia)
                    ->delete();
                continue;
            }

            HorarioAtencionSede::updateOrCreate(
                ['sede_id' => $sede->id, 'dia_semana' => $dia],
                ['hora_inicio' => $inicio, 'hora_fin' => $fin]
            );
        }

        return redirect()
            ->route('admin.horarios-atencion.edit', $sede->id)
            ->with('success', 'Horario de atención de la sede actualizado correctamente');
    }
}
