<?php

namespace App\Http\Controllers;

use App\Models\Galeria;
use Illuminate\Http\Request;

class GaleriaController extends Controller
{
    public function index()
    {
        $galerias = Galeria::orderBy('orden')
            ->get();

        return view(
            'admin.galerias.index',
            compact('galerias')
        );
    }

    public function create()
    {
        return view('admin.galerias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp,avif',
            'orden' => 'required|integer',
            'estado' => 'required',
        ]);

        $rutaImagen = '';

        if ($request->hasFile('imagen')) {

            $archivo = $request->file('imagen');

            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();

            $rutaDestino =
                public_path('uploads/galerias/');

            $archivo->move(
                $rutaDestino,
                $nombreArchivo
            );

            $rutaImagen =
                'uploads/galerias/' .
                $nombreArchivo;
        }

        $galeria = new Galeria();

        $galeria->titulo = $request->titulo;
        $galeria->descripcion = $request->descripcion;
        $galeria->imagen = $rutaImagen;
        $galeria->orden = $request->orden;
        $galeria->estado = $request->estado;

        $galeria->save();

        return redirect()
            ->route('admin.galerias.index')
            ->with(
                'success',
                'Imagen agregada correctamente'
            );
    }

    public function edit($id)
    {
        $galeria = Galeria::findOrFail($id);

        return view(
            'admin.galerias.edit',
            compact('galeria')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'orden' => 'required|integer',
            'estado' => 'required',
        ]);

        $galeria = Galeria::findOrFail($id);

        $galeria->titulo = $request->titulo;
        $galeria->descripcion = $request->descripcion;
        $galeria->orden = $request->orden;
        $galeria->estado = $request->estado;

        if ($request->hasFile('imagen')) {

            // Eliminar imagen anterior
            if (
                $galeria->imagen &&
                file_exists(public_path($galeria->imagen))
            ) {
                unlink(public_path($galeria->imagen));
            }

            // Guardar nueva imagen
            $archivo = $request->file('imagen');

            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();

            $rutaDestino =
                public_path('uploads/galerias/');

            $archivo->move(
                $rutaDestino,
                $nombreArchivo
            );

            $galeria->imagen =
                'uploads/galerias/' .
                $nombreArchivo;
        }

        $galeria->save();

        return redirect()
            ->route('admin.galerias.index')
            ->with(
                'success',
                'Imagen actualizada correctamente'
            );
    }

    public function destroy($id)
    {
        $galeria = Galeria::findOrFail($id);

        if (
            $galeria->imagen &&
            file_exists(public_path($galeria->imagen))
        ) {
            unlink(public_path($galeria->imagen));
        }

        $galeria->delete();

        return redirect()
            ->route('admin.galerias.index')
            ->with(
                'success',
                'Imagen eliminada correctamente'
            );
    }
}
