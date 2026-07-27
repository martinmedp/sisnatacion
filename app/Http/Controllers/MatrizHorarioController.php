<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Docente;
use App\Models\Horario;
use App\Services\HorarioMatrizService;
use Carbon\Carbon;

class MatrizHorarioController extends Controller
{
    private array $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

    private function normalizarHora(?string $hora): ?string
    {
        return $hora ? substr($hora, 0, 5) : null;
    }

    public function index()
    {
        $sedes = Sede::where('estado', 'activo')->orderBy('nombre')->get();
        $docentes = Docente::where('estado', 'ACTIVO')->orderBy('nombre_completo')->get();

        return view('admin.matriz-horarios.index', compact('sedes', 'docentes'));
    }

    public function irSede(\Illuminate\Http\Request $request)
    {
        return redirect()->route('admin.matriz-horarios.sede', $request->sede_id);
    }

    public function irDocente(\Illuminate\Http\Request $request)
    {
        return redirect()->route('admin.matriz-horarios.docente', $request->docente_id);
    }

    /**
     * Matriz interactiva de una sede: verde = libre, amarillo = ya
     * hay grupo(s) asignado(s) ahí (no bloqueante, pueden coexistir
     * varios grupos en la misma franja).
     */
    public function porSede($sedeId)
    {
        $sede = Sede::with('horariosAtencion')->findOrFail($sedeId);

        $atencionPorDia = $sede->horariosAtencion->keyBy('dia_semana')->map(function ($a) {
            $a->hora_inicio = $this->normalizarHora($a->hora_inicio);
            $a->hora_fin = $this->normalizarHora($a->hora_fin);
            return $a;
        });

        if ($atencionPorDia->isEmpty()) {
            return view('admin.matriz-horarios.sede', [
                'sede' => $sede,
                'dias' => $this->dias,
                'sinConfigurar' => true,
                'filas' => [],
            ]);
        }

        $duracion = $sede->duracion_clase_minutos ?: 45;

        $minInicio = $atencionPorDia->min(fn ($a) => $a->hora_inicio);
        $maxFin = $atencionPorDia->max(fn ($a) => $a->hora_fin);

        $filas = [];
        $cursor = Carbon::parse($minInicio);
        $fin = Carbon::parse($maxFin);

        $horariosSede = Horario::with('grupo.nivel', 'grupo.docente')
            ->whereHas('grupo', fn ($q) => $q->where('sede_id', $sedeId))
            ->where('estado', 'activo')
            ->get()
            ->map(function ($h) {
                $h->hora_inicio = $this->normalizarHora($h->hora_inicio);
                $h->hora_fin = $this->normalizarHora($h->hora_fin);
                return $h;
            });

        while ($cursor->lt($fin)) {
            $slotInicio = $cursor->format('H:i');
            $slotFin = $cursor->copy()->addMinutes($duracion)->format('H:i');

            $celdas = [];
            foreach ($this->dias as $dia) {
                $atencion = $atencionPorDia->get($dia);

                if (!$atencion || $slotInicio < $atencion->hora_inicio || $slotFin > $atencion->hora_fin) {
                    $celdas[$dia] = ['estado' => 'cerrado', 'grupos' => []];
                    continue;
                }

                $ocupantes = $horariosSede->filter(function ($h) use ($dia, $slotInicio, $slotFin) {
                    return $h->dia_semana === $dia
                        && $h->hora_inicio < $slotFin
                        && $h->hora_fin > $slotInicio;
                });

                $celdas[$dia] = [
                    'estado' => $ocupantes->isEmpty() ? 'libre' : 'ocupado',
                    'grupos' => $ocupantes->map(fn ($h) => [
                        'grupo'   => $h->grupo->nombre ?? '—',
                        'nivel'   => $h->grupo->nivel->nombre ?? '—',
                        'docente' => $h->grupo->docente->nombre_completo ?? '—',
                    ])->values(),
                ];
            }

            $filas[] = [
                'inicio' => $slotInicio,
                'fin'    => $slotFin,
                'celdas' => $celdas,
            ];

            $cursor->addMinutes($duracion);
        }

        return view('admin.matriz-horarios.sede', [
            'sede' => $sede,
            'dias' => $this->dias,
            'sinConfigurar' => false,
            'filas' => $filas,
        ]);
    }

    /**
     * Matriz de disponibilidad de un docente (vista del administrador,
     * puede consultar la de cualquier docente). La construcción de la
     * matriz vive en HorarioMatrizService, compartida con la vista que
     * el propio docente usa para ver su horario personal.
     */
    public function porDocente($docenteId)
    {
        $docente = Docente::findOrFail($docenteId);

        $matriz = HorarioMatrizService::matrizDocente($docenteId);

        return view('admin.matriz-horarios.docente', [
            'docente' => $docente,
            'dias'    => $matriz['dias'],
            'filas'   => $matriz['filas'],
        ]);
    }
}
