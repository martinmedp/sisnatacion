<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('orden', 'asc')->get();

        return view('admin.banners.index', compact('banners'));
    }


    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $banner = new Banner();

        $banner->titulo = $request->titulo;
        $banner->subtitulo = $request->subtitulo;
        $banner->texto_boton = $request->texto_boton;
        $banner->url_boton = $request->url_boton;
        $banner->orden = $request->orden;
        $banner->estado = $request->estado;

        if ($request->hasFile('imagen')) {

            $archivo = $request->file('imagen');

            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();

            $rutaDestino = public_path('uploads/banners/');

            $archivo->move($rutaDestino, $nombreArchivo);

            $banner->imagen = 'uploads/banners/' . $nombreArchivo;
        }

        $banner->save();
        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner guardado correctamente');
        //dd('Banner guardado con imagen');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        return view(
            'admin.banners.edit',
            compact('banner')
        );
    }


    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $banner->titulo = $request->titulo;
        $banner->subtitulo = $request->subtitulo;
        $banner->texto_boton = $request->texto_boton;
        $banner->url_boton = $request->url_boton;
        $banner->orden = $request->orden;
        $banner->estado = $request->estado;
        // Verificar si se ha subido una nueva imagen
        if ($request->hasFile('imagen')) {
            if (
                $banner->imagen &&
                file_exists(public_path($banner->imagen))
            ) {
                unlink(public_path($banner->imagen));
            }
            $archivo = $request->file('imagen');
            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();
            $rutaDestino =
                public_path('uploads/banners/');
            $archivo->move(
                $rutaDestino,
                $nombreArchivo
            );
            $banner->imagen =
                'uploads/banners/' .
                $nombreArchivo;
        }

        $banner->save();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner actualizado correctamente');
    }
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if (
            $banner->imagen &&
            file_exists(public_path($banner->imagen))
        ) {
            unlink(public_path($banner->imagen));
        }
        $banner->delete();
        return redirect()
            ->route('admin.banners.index')
            ->with(
                'success',
                'Banner eliminado correctamente'
            );
    }
}
