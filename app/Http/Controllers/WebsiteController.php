<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Noticia;
use App\Models\Galeria;


class WebsiteController extends Controller
{
    public function inicio()
    {
        $banners = Banner::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy('orden')
            ->get();

        $ultimasNoticias = Noticia::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy(
                'fecha_publicacion',
                'desc'
            )
            ->take(3)
            ->get();

        $ultimasGalerias = Galeria::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy(
                'orden'
            )
            ->take(6)
            ->get();

        return view(
            'website.inicio',
            compact(
                'banners',
                'ultimasNoticias',
                'ultimasGalerias'
            )
        );
    }






    public function nosotros()
    {
        return view('website.nosotros');
    }
    public function contacto()
    {
        return view('website.contacto');
    }

    // Método para mostrar la página de admisiones
    public function admisiones()
    {
        return view('website.admisiones');
    }

    // Métodos para mostrar las noticias
    public function noticias()
    {
        $noticias = Noticia::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy(
                'fecha_publicacion',
                'desc'
            )
            ->get();

        return view(
            'website.noticias',
            compact('noticias')
        );
    }
    public function noticiaDetalle(Noticia $noticia)
    {
        if ($noticia->estado !== 'ACTIVO') {
            abort(404);
        }

        return view(
            'website.noticia-detalle',
            compact('noticia')
        );
    }




    // Métodos para mostrar la galería

    public function galeria()
    {
        $galerias = Galeria::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy('orden')
            ->get();
        return view(
            'website.galeria',
            compact('galerias')
        );
    }
}
