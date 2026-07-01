<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::orderBy('id', 'asc')->get();

        return view('admin.sedes.index', compact('sedes'));
    }

    public function create()
    {
        return view('admin.sedes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'encargado' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Sede::create($request->only([
            'nombre',
            'direccion',
            'telefono',
            'encargado',
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

        return view('admin.sedes.edit', compact('sede'));
    }

    public function update(Request $request, $id)
    {
        $sede = Sede::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'encargado' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $sede->update($request->only([
            'nombre',
            'direccion',
            'telefono',
            'encargado',
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
