<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Noticia;
use App\Models\Galeria;
use App\Models\Configuracion;
use App\Models\Docente;

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
        $configuracion = Configuracion::first();

        return view(
            'website.inicio',
            compact(
                'banners',
                'ultimasNoticias',
                'ultimasGalerias',
                'configuracion'
            )
        );
    }

    // Método para mostrar la página de nosotros
    public function nosotros()
    {
        $configuracion = Configuracion::first();

        return view(
            'website.nosotros',
            compact('configuracion')
        );
    }
    public function contacto()
    {
        $configuracion = Configuracion::first();

        return view(
            'website.contacto',
            compact('configuracion')
        );
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


    public function docentes()
    {
        $docentes = Docente::where(
            'estado',
            'ACTIVO'
        )
            ->orderBy('orden')
            ->get();

        return view(
            'website.docentes',
            compact('docentes')
        );
    }
}
