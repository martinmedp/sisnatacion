<?php

namespace App\Services;

use App\Models\Horario;
use Carbon\Carbon;

class HorarioMatrizService
{
    private static array $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

    private static function normalizarHora(?string $hora): ?string
    {
        return $hora ? substr($hora, 0, 5) : null;
    }

    private static function mcd(int $a, int $b): int
    {
        return $b === 0 ? $a : self::mcd($b, $a % $b);
    }

    /**
     * Construye la matriz de disponibilidad de un docente, combinando
     * todas las sedes donde dicta clase. El tamaño de cada casilla es
     * el máximo común divisor entre las duraciones de clase de esas
     * sedes, para no partir ninguna clase real en dos casillas.
     *
     * Usado tanto por la matriz del administrador (por cualquier
     * docente) como por el propio docente al ver su horario personal.
     *
     * @return array{dias: array, filas: array}
     */
    public static function matrizDocente(int $docenteId): array
    {
        $horariosDocente = Horario::with('grupo.nivel', 'grupo.sede')
            ->whereHas('grupo', fn ($q) => $q->where('docente_id', $docenteId))
            ->where('estado', 'activo')
            ->get()
            ->map(function ($h) {
                $h->hora_inicio = self::normalizarHora($h->hora_inicio);
                $h->hora_fin = self::normalizarHora($h->hora_fin);
                return $h;
            });

        $minInicio = $horariosDocente->min('hora_inicio') ?? '06:00';
        $maxFin = $horariosDocente->max('hora_fin') ?? '20:00';

        $duraciones = $horariosDocente
            ->map(fn ($h) => $h->grupo->sede->duracion_clase_minutos ?? 30)
            ->unique()
            ->values();

        $duracion = $duraciones->isEmpty()
            ? 30
            : $duraciones->reduce(fn ($acumulado, $item) => $acumulado === null ? $item : self::mcd($acumulado, $item));

        $filas = [];
        $cursor = Carbon::parse($minInicio);
        $fin = Carbon::parse($maxFin);

        while ($cursor->lt($fin)) {
            $slotInicio = $cursor->format('H:i');
            $slotFin = $cursor->copy()->addMinutes($duracion)->format('H:i');

            $celdas = [];
            foreach (self::$dias as $dia) {
                $ocupantes = $horariosDocente->filter(function ($h) use ($dia, $slotInicio, $slotFin) {
                    return $h->dia_semana === $dia
                        && $h->hora_inicio < $slotFin
                        && $h->hora_fin > $slotInicio;
                });

                $celdas[$dia] = [
                    'estado' => $ocupantes->isEmpty() ? 'libre' : 'ocupado',
                    'grupos' => $ocupantes->map(fn ($h) => [
                        'grupo' => $h->grupo->nombre ?? '—',
                        'sede'  => $h->grupo->sede->nombre ?? '—',
                        'nivel' => $h->grupo->nivel->nombre ?? '—',
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

        return [
            'dias'  => self::$dias,
            'filas' => $filas,
        ];
    }
}
