<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    //
    public function index()
    {
        //Obtener desde el json, la Api los datos de monedas, y enviarlos a la vista para que el usuario pueda seleccionar la moneda de su institución
        $jsonData = file_get_contents('https://api.hilariweb.com/divisas');
        $divisas = json_decode($jsonData, true);



        $configuracion = \App\Models\Configuracion::first(); // Obtener la configuración actual (si existe)
        // Aquí puedes cargar la vista de configuración del sistema
        return view('admin.configuracion.index', compact('configuracion', 'divisas'));
    }

    public function create(Request $request)
    {
        /* $datos = $request->all();
        return response()->json($datos); */
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'telefono1' => 'required',
            'correo_electronico' => 'required|email',
            'divisa' => 'required',
            'web' => 'required|url|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,svg',
        ]);
        // Verificar si ya existe una configuración
        $configuracion = Configuracion::first();
        if ($configuracion) {
            // Si existe, actualizar la configuración
            $configuracion->nombre = $request->nombre;
            $configuracion->descripcion = $request->descripcion;
            $configuracion->direccion = $request->direccion;
            $configuracion->telefono1 = $request->telefono1;
            $configuracion->divisa = $request->divisa;
            $configuracion->correo_electronico = $request->correo_electronico;
            $configuracion->web = $request->web;
            // Manejar la carga del logo
            if ($request->hasFile('logo')) {
                if ($configuracion->logo && file_exists(public_path($configuracion->logo))) {
                    // Eliminar el logo anterior si existe
                    unlink(public_path($configuracion->logo));
                }
                //guardar el nuevo logo y actualizar la ruta en la configuración
                /* $logoPath = $request->file('logo')->store('logos', 'public');
                $configuracion->logo = '/storage/' . $logoPath; */

                $logoPath = $request->file('logo');
                $nombreArchivo = time() . '_' . $logoPath->getClientOriginalName();
                $rutaDestino = public_path('uploads/logos/');
                $logoPath->move($rutaDestino, $nombreArchivo);
                $configuracion->logo = 'uploads/logos/' . $nombreArchivo;
            }
            $configuracion->save();
            return redirect()->route('admin.configuracion.index')->with('success', 'Configuración actualizada correctamente');
        } else {
            // Si no existe, crear una nueva configuración
            $configuracion = new Configuracion();
            $configuracion->nombre = $request->nombre;
            $configuracion->descripcion = $request->descripcion;
            $configuracion->direccion = $request->direccion;
            $configuracion->telefono1 = $request->telefono1;
            $configuracion->divisa = $request->divisa;
            $configuracion->correo_electronico = $request->correo_electronico;
            $configuracion->web = $request->web;
            // Manejar la carga del logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo');
                $nombreArchivo = time() . '_' . $logoPath->getClientOriginalName();
                $rutaDestino = public_path('uploads/logos/');
                $logoPath->move($rutaDestino, $nombreArchivo);
                $configuracion->logo = 'uploads/logos/' . $nombreArchivo;
            }
            $configuracion->save();
            return redirect()->route('admin.configuracion.index')->with('success', 'Configuración creada correctamente');
        }
    }
}
