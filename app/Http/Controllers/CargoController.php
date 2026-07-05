<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::orderBy('nombre', 'asc')->get();

        return view('admin.cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('admin.cargos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Cargo::create($request->only(['nombre', 'descripcion', 'estado']));

        return redirect()
            ->route('admin.cargos.index')
            ->with('success', 'Cargo guardado correctamente');
    }

    public function edit($id)
    {
        $cargo = Cargo::findOrFail($id);

        return view('admin.cargos.edit', compact('cargo'));
    }

    public function update(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $cargo->update($request->only(['nombre', 'descripcion', 'estado']));

        return redirect()
            ->route('admin.cargos.index')
            ->with('success', 'Cargo actualizado correctamente');
    }

    public function destroy($id)
    {
        $cargo = Cargo::findOrFail($id);
        $cargo->delete();

        return redirect()
            ->route('admin.cargos.index')
            ->with('success', 'Cargo eliminado correctamente');
    }
}
