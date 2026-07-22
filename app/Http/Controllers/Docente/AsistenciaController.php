<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    /**
     * Muestra el formulario para marcar la asistencia de todos los
     * alumnos activos de un grupo, en una fecha determinada (hoy por
     * defecto). Si ya existe asistencia registrada para esa fecha,
     * la precarga para poder editarla.
     */
    public function index(Request $request, $grupoId)
    {
        $docente = $this->docenteAutenticado();

        $grupo = Grupo::where('id', $grupoId)
            ->where('docente_id', $docente->id)
            ->firstOrFail();

        if ($grupo->horarios()->count() === 0) {
            return redirect()
                ->route('docente.grupos.alumnos', $grupoId)
                ->with('error', 'Este grupo no tiene horarios asignados. No se puede registrar asistencia hasta que el administrador configure al menos un horario para el grupo.');
        }

        $fecha = $request->get('fecha', now()->toDateString());

        $matriculas = $grupo->matriculas()
            ->with(['alumno', 'asistencias' => fn ($q) => $q->where('fecha', $fecha)])
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->alumno->nombre_completo ?? '');

        return view('panel-docente.asistencia', compact('docente', 'grupo', 'matriculas', 'fecha'));
    }

    public function store(Request $request, $grupoId)
    {
        $docente = $this->docenteAutenticado();

        $grupo = Grupo::where('id', $grupoId)
            ->where('docente_id', $docente->id)
            ->firstOrFail();

        if ($grupo->horarios()->count() === 0) {
            return redirect()
                ->route('docente.grupos.alumnos', $grupoId)
                ->with('error', 'Este grupo no tiene horarios asignados. No se puede registrar asistencia.');
        }

        $request->validate([
            'fecha'               => 'required|date',
            'estados'             => 'required|array',
            'estados.*'           => 'required|in:presente,ausente,tarde,excusa',
            'observaciones'       => 'nullable|array',
        ]);

        // Verificar que todas las matrículas enviadas pertenezcan a este grupo
        $matriculasValidas = $grupo->matriculas()->where('estado', 'activa')->pluck('id');

        foreach ($request->estados as $matriculaId => $estado) {
            if (!$matriculasValidas->contains($matriculaId)) {
                continue;
            }

            Asistencia::updateOrCreate(
                ['matricula_id' => $matriculaId, 'fecha' => $request->fecha],
                [
                    'docente_id'    => $docente->id,
                    'estado'        => $estado,
                    'observaciones' => $request->observaciones[$matriculaId] ?? null,
                ]
            );
        }

        return redirect()
            ->route('docente.asistencia.index', ['grupoId' => $grupoId, 'fecha' => $request->fecha])
            ->with('success', 'Asistencia del ' . $request->fecha . ' guardada correctamente');
    }

    /**
     * Muestra un resumen mensual de asistencia por alumno del grupo:
     * cuántas clases se registraron ese mes y el desglose de su
     * asistencia (presentes, ausencias, tardanzas, excusas).
     */
    public function resumen(Request $request, $grupoId)
    {
        $docente = $this->docenteAutenticado();

        $grupo = Grupo::where('id', $grupoId)
            ->where('docente_id', $docente->id)
            ->firstOrFail();

        $mes = $request->get('mes', now()->format('Y-m'));
        $inicioMes = \Carbon\Carbon::parse($mes . '-01')->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        $matriculas = $grupo->matriculas()
            ->with(['alumno', 'asistencias' => fn ($q) => $q->whereBetween('fecha', [$inicioMes, $finMes])])
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->alumno->nombre_completo ?? '');

        $resumen = $matriculas->map(function ($matricula) {
            $asistencias = $matricula->asistencias;

            return [
                'alumno'          => $matricula->alumno,
                'clasesDelMes'    => $asistencias->count(),
                'presentes'       => $asistencias->where('estado', 'presente')->count(),
                'ausentes'        => $asistencias->where('estado', 'ausente')->count(),
                'tardanzas'       => $asistencias->where('estado', 'tarde')->count(),
                'excusas'         => $asistencias->where('estado', 'excusa')->count(),
            ];
        });

        return view('panel-docente.asistencia-resumen', compact('docente', 'grupo', 'mes', 'inicioMes', 'resumen'));
    }

    private function docenteAutenticado(): Docente
    {
        $docente = Docente::where('user_id', auth()->id())->first();
        abort_if(!$docente, 403);

        return $docente;
    }
}
