<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Configuracion;

class CodigoAlumnoService
{
    /**
     * Genera un código único para el alumno con base en la sigla
     * del nombre de la institución + un consecutivo.
     *
     * Ejemplo: "Academia Natación Delfines" -> "AND-0001"
     */
    public static function generar(): string
    {
        $sigla = self::obtenerSigla();

        $ultimoNumero = Alumno::where('codigo', 'like', $sigla . '-%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(codigo, '-', -1) AS UNSIGNED)) as ultimo")
            ->value('ultimo');

        $siguiente = ($ultimoNumero ?? 0) + 1;

        return $sigla . '-' . str_pad($siguiente, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Extrae la sigla del nombre de la institución configurada.
     * Toma la primera letra de cada palabra significativa.
     */
    protected static function obtenerSigla(): string
    {
        $config = Configuracion::first();
        $nombre = $config->nombre ?? 'Academia Natacion';

        $palabrasIgnorar = ['de', 'la', 'el', 'los', 'las', 'y', 'del'];

        $palabras = preg_split('/\s+/', trim($nombre));

        $letras = collect($palabras)
            ->filter(fn ($palabra) => !in_array(mb_strtolower($palabra), $palabrasIgnorar))
            ->map(fn ($palabra) => mb_strtoupper(mb_substr($palabra, 0, 1)))
            ->implode('');

        return $letras ?: 'AL';
    }
}
