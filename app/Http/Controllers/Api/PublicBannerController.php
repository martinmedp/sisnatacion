<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class PublicBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy('orden')
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'titulo' => $banner->titulo,
                    'subtitulo' => $banner->subtitulo,
                    'imagen' => asset(
                        $banner->imagen
                    ),
                    'texto_boton' =>
                    $banner->texto_boton,
                    'url_boton' =>
                    $banner->url_boton,
                ];
            });
        return response()->json(
            $banners
        );
    }
}
