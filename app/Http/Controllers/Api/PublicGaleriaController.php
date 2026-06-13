<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeria;

class PublicGaleriaController extends Controller
{
    public function index()
    {
        $galerias = Galeria::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy('orden')
            ->get()
            ->map(function ($galeria) {
                return [
                    'id' => $galeria->id,
                    'titulo' =>
                    $galeria->titulo,
                    'descripcion' =>
                    $galeria->descripcion,
                    'imagen' =>
                    $galeria->imagen
                        ? asset($galeria->imagen)
                        : null,
                ];
            });
        return response()->json(
            $galerias
        );
    }
}
