<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nivel;
use App\Models\CriterioEvaluacion;

class NivelesCriteriosSeeder extends Seeder
{
    public function run(): void
    {
        $niveles = [
            [
                'nombre' => 'Familiarización',
                'descripcion' => 'Adaptación al medio acuático, pérdida del miedo al agua.',
                'orden' => 1,
                'edad_minima' => 3,
                'edad_maxima' => 6,
                'duracion_meses' => 2,
                'valor_clase' => 300000,
                'estado' => 'activo',
                'criterios' => [
                    'Ingresa al agua sin resistencia ni llanto',
                    'Moja su cara con tranquilidad',
                    'Realiza burbujas soplando dentro del agua',
                    'Flota boca arriba con apoyo del docente',
                    'Se desplaza con flotadores en trayecto corto',
                ],
            ],
            [
                'nombre' => 'Flotación y respiración',
                'descripcion' => 'Control de la respiración y flotación autónoma básica.',
                'orden' => 2,
                'edad_minima' => 5,
                'edad_maxima' => 9,
                'duracion_meses' => 3,
                'valor_clase' => 450000,
                'estado' => 'activo',
                'criterios' => [
                    'Flota boca abajo sin apoyo durante 10 segundos',
                    'Flota boca arriba sin apoyo durante 10 segundos',
                    'Realiza respiración lateral coordinada',
                    'Se desplaza 5 metros con flotador',
                    'Recupera posición vertical desde flotación',
                ],
            ],
            [
                'nombre' => 'Iniciación a estilos',
                'descripcion' => 'Introducción a los movimientos básicos de los 4 estilos.',
                'orden' => 3,
                'edad_minima' => 6,
                'edad_maxima' => 12,
                'duracion_meses' => 3,
                'valor_clase' => 500000,
                'estado' => 'activo',
                'criterios' => [
                    'Realiza patada de crol sujetado de la pared',
                    'Realiza patada de espalda sujetado de la pared',
                    'Coordina brazada básica de crol',
                    'Nada 10 metros de crol con respiración lateral',
                    'Nada 10 metros de espalda',
                ],
            ],
            [
                'nombre' => 'Consolidación de estilos',
                'descripcion' => 'Perfeccionamiento de la técnica en crol y espalda, inicio de pecho.',
                'orden' => 4,
                'edad_minima' => 7,
                'edad_maxima' => 13,
                'duracion_meses' => 4,
                'valor_clase' => 550000,
                'estado' => 'activo',
                'criterios' => [
                    'Nada 25 metros de crol con técnica correcta',
                    'Nada 25 metros de espalda con técnica correcta',
                    'Realiza patada de pecho de forma coordinada',
                    'Ejecuta viraje básico en la pared',
                    'Mantiene resistencia en 50 metros continuos',
                ],
            ],
            [
                'nombre' => 'Perfeccionamiento técnico',
                'descripcion' => 'Refinamiento técnico de los 4 estilos y resistencia.',
                'orden' => 5,
                'edad_minima' => 8,
                'edad_maxima' => 15,
                'duracion_meses' => 4,
                'valor_clase' => 600000,
                'estado' => 'activo',
                'criterios' => [
                    'Nada 50 metros de crol con técnica avanzada',
                    'Nada 50 metros de pecho con técnica avanzada',
                    'Nada 25 metros de mariposa',
                    'Ejecuta salida de bloque de forma correcta',
                    'Mantiene resistencia en 100 metros combinados',
                ],
            ],
            [
                'nombre' => 'Preparación competitiva',
                'descripcion' => 'Entrenamiento orientado a la competencia federada.',
                'orden' => 6,
                'edad_minima' => 10,
                'edad_maxima' => 18,
                'duracion_meses' => 6,
                'valor_clase' => 700000,
                'estado' => 'activo',
                'criterios' => [
                    'Domina los 4 estilos con técnica competitiva',
                    'Ejecuta salidas y virajes reglamentarios',
                    'Completa 200 metros combinado individual',
                    'Cumple marcas mínimas de tiempo por estilo',
                    'Participa en al menos una competencia interna',
                ],
            ],
        ];

        foreach ($niveles as $datosNivel) {
            $criterios = $datosNivel['criterios'];
            unset($datosNivel['criterios']);

            $nivel = Nivel::firstOrCreate(
                ['nombre' => $datosNivel['nombre']],
                $datosNivel
            );

            foreach ($criterios as $orden => $nombreCriterio) {
                CriterioEvaluacion::firstOrCreate(
                    ['nivel_id' => $nivel->id, 'nombre' => $nombreCriterio],
                    ['orden' => $orden + 1, 'estado' => 'activo']
                );
            }
        }
    }
}
