<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Descuento;

class DescuentoController extends Controller
{
    public function index()
    {
        $descuentos = Descuento::orderBy('nombre', 'asc')->get();

        return view('admin.descuentos.index', compact('descuentos'));
    }

    public function create()
    {
        return view('admin.descuentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,valor_fijo',
            'valor' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Descuento::create($request->only([
            'nombre', 'descripcion', 'tipo', 'valor', 'estado',
        ]));

        return redirect()
            ->route('admin.descuentos.index')
            ->with('success', 'Descuento guardado correctamente');
    }

    public function edit($id)
    {
        $descuento = Descuento::findOrFail($id);

        return view('admin.descuentos.edit', compact('descuento'));
    }

    public function update(Request $request, $id)
    {
        $descuento = Descuento::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,valor_fijo',
            'valor' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $descuento->update($request->only([
            'nombre', 'descripcion', 'tipo', 'valor', 'estado',
        ]));

        return redirect()
            ->route('admin.descuentos.index')
            ->with('success', 'Descuento actualizado correctamente');
    }

    public function destroy($id)
    {
        $descuento = Descuento::findOrFail($id);
        $descuento->delete();

        return redirect()
            ->route('admin.descuentos.index')
            ->with('success', 'Descuento eliminado correctamente');
    }
}
