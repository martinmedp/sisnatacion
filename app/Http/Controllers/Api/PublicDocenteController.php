<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Docente;

class PublicDocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy('orden')
            ->get()
            ->map(function ($docente) {

                return [

                    'id' => $docente->id,

                    'nombre_completo' =>
                    $docente->nombre_completo,
                    'foto' =>
                    $docente->foto
                        ? asset($docente->foto)
                        : null,
                    'cargo' =>
                    $docente->cargo,
                    'profesion' =>
                    $docente->profesion,
                    'nivel_academico' =>
                    $docente->nivel_academico,
                    'perfil' =>
                    $docente->perfil,
                ];
            });
        return response()->json(
            $docentes
        );
    }
}
