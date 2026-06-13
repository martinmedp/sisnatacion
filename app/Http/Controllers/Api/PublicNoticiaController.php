<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;

class PublicNoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy(
                'fecha_publicacion',
                'desc'
            )
            ->get()
            ->map(function ($noticia) {
                return [
                    'id' => $noticia->id,
                    'titulo' =>
                    $noticia->titulo,
                    'resumen' =>
                    $noticia->resumen,
                    'fecha_publicacion' =>
                    $noticia->fecha_publicacion,
                    'imagen' =>
                    $noticia->imagen
                        ? asset($noticia->imagen)
                        : null,
                ];
            });
        return response()->json(
            $noticias
        );
    }
}
