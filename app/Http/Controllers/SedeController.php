<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;
use App\Models\Docente;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::with('encargado')->orderBy('nombre', 'asc')->get();

        return view('admin.sedes.index', compact('sedes'));
    }

    public function create()
    {
        $docentes = Docente::where('estado', 'ACTIVO')->orderBy('nombre_completo')->get();

        return view('admin.sedes.create', compact('docentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'encargado_id' => 'nullable|exists:docentes,id',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Sede::create($request->only([
            'nombre',
            'direccion',
            'telefono',
            'encargado_id',
            'descripcion',
            'estado',
        ]));

        return redirect()
            ->route('admin.sedes.index')
            ->with('success', 'Sede guardada correctamente');
    }

    public function edit($id)
    {
        $sede = Sede::findOrFail($id);
        $docentes = Docente::where('estado', 'ACTIVO')->orderBy('nombre_completo')->get();

        return view('admin.sedes.edit', compact('sede', 'docentes'));
    }

    public function update(Request $request, $id)
    {
        $sede = Sede::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'encargado_id' => 'nullable|exists:docentes,id',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $sede->update($request->only([
            'nombre',
            'direccion',
            'telefono',
            'encargado_id',
            'descripcion',
            'estado',
        ]));

        return redirect()
            ->route('admin.sedes.index')
            ->with('success', 'Sede actualizada correctamente');
    }

    public function destroy($id)
    {
        $sede = Sede::findOrFail($id);
        $sede->delete();

        return redirect()
            ->route('admin.sedes.index')
            ->with('success', 'Sede eliminada correctamente');
    }
}
