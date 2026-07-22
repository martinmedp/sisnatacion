<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Alumno;
use App\Models\Observador;
use Illuminate\Http\Request;

class ObservadorController extends Controller
{
    /**
     * Muestra el historial del libro observador de un alumno y el
     * formulario para agregar una nueva anotación. Verifica que el
     * alumno esté matriculado en algún grupo de este docente.
     */
    public function index($alumnoId)
    {
        $docente = $this->docenteAutenticado();

        $alumno = Alumno::with(['observador.docente'])
            ->where('id', $alumnoId)
            ->whereHas('matriculas.grupo', fn ($q) => $q->where('docente_id', $docente->id))
            ->firstOrFail();

        return view('panel-docente.observador', compact('docente', 'alumno'));
    }

    public function store(Request $request, $alumnoId)
    {
        $docente = $this->docenteAutenticado();

        $alumno = Alumno::where('id', $alumnoId)
            ->whereHas('matriculas.grupo', fn ($q) => $q->where('docente_id', $docente->id))
            ->firstOrFail();

        $request->validate([
            'tipo'        => 'required|in:comportamiento,conducta,rendimiento,otro',
            'fecha'       => 'required|date',
            'descripcion' => 'required|string|max:1000',
        ]);

        Observador::create([
            'alumno_id'   => $alumno->id,
            'docente_id'  => $docente->id,
            'tipo'        => $request->tipo,
            'fecha'       => $request->fecha,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()
            ->route('docente.observador.index', $alumno->id)
            ->with('success', 'Anotación registrada correctamente');
    }

    private function docenteAutenticado(): Docente
    {
        $docente = Docente::where('user_id', auth()->id())->first();
        abort_if(!$docente, 403);

        return $docente;
    }
}
