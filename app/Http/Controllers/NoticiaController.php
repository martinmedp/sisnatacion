<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::orderBy('fecha_publicacion', 'desc')->get();
        return view(
            'admin.noticias.index',
            compact('noticias')
        );
    }
    public function create()
    {
        return view('admin.noticias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'resumen' => 'required|max:500',
            'contenido' => 'required',
            'estado' => 'required',
            'fecha_publicacion' => 'required|date',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        $rutaImagen = '';

        if ($request->hasFile('imagen')) {

            $archivo = $request->file('imagen');

            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();

            $rutaDestino =
                public_path('uploads/noticias/');

            $archivo->move(
                $rutaDestino,
                $nombreArchivo
            );

            $rutaImagen =
                'uploads/noticias/' .
                $nombreArchivo;
        }

        $noticia = new Noticia();

        $noticia->titulo = $request->titulo;
        $noticia->resumen = $request->resumen;
        $noticia->contenido = $request->contenido;
        $noticia->imagen = $rutaImagen;
        $noticia->estado = $request->estado;
        $noticia->fecha_publicacion = $request->fecha_publicacion;


        $noticia->save();
        return redirect()->route('admin.noticias.index')->with('success', 'Noticia creada correctamente');
    }

    public function edit($id)
    {
        $noticia = Noticia::findOrFail($id);

        return view(
            'admin.noticias.edit',
            compact('noticia')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'resumen' => 'required|max:500',
            'contenido' => 'required',
            'estado' => 'required',
            'fecha_publicacion' => 'required|date',
        ]);

        $noticia = Noticia::findOrFail($id);

        $noticia->titulo = $request->titulo;
        $noticia->resumen = $request->resumen;
        $noticia->contenido = $request->contenido;
        $noticia->estado = $request->estado;
        $noticia->fecha_publicacion = $request->fecha_publicacion;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if (
                $noticia->imagen &&
                file_exists(public_path($noticia->imagen))
            ) {
                unlink(public_path($noticia->imagen));
            }
            // Guardar nueva imagen
            $archivo = $request->file('imagen');
            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();
            $rutaDestino =
                public_path('uploads/noticias/');
            $archivo->move(
                $rutaDestino,
                $nombreArchivo
            );
            $noticia->imagen =
                'uploads/noticias/' .
                $nombreArchivo;
        }
        $noticia->save();

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia actualizada correctamente');
    }

    public function destroy($id)
    {
        $noticia = Noticia::findOrFail($id);

        // Eliminar imagen física
        if (
            $noticia->imagen &&
            file_exists(public_path($noticia->imagen))
        ) {
            unlink(public_path($noticia->imagen));
        }

        $noticia->delete();

        return redirect()
            ->route('admin.noticias.index')
            ->with(
                'success',
                'Noticia eliminada correctamente'
            );
    }
}
