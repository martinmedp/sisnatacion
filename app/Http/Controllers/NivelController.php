<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nivel;

class NivelController extends Controller
{
    public function index()
    {
        $niveles = Nivel::orderBy('orden', 'asc')->get();

        return view('admin.niveles.index', compact('niveles'));
    }

    public function create()
    {
        return view('admin.niveles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'required|integer|min:0',
            'edad_minima' => 'nullable|integer|min:0',
            'edad_maxima' => 'nullable|integer|min:0|gte:edad_minima',
            'valor_clase' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Nivel::create($request->only([
            'nombre', 'descripcion', 'orden', 'edad_minima', 'edad_maxima', 'valor_clase', 'estado',
        ]));

        return redirect()
            ->route('admin.niveles.index')
            ->with('success', 'Nivel guardado correctamente');
    }

    public function edit($id)
    {
        $nivel = Nivel::findOrFail($id);

        return view('admin.niveles.edit', compact('nivel'));
    }

    public function update(Request $request, $id)
    {
        $nivel = Nivel::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'required|integer|min:0',
            'edad_minima' => 'nullable|integer|min:0',
            'edad_maxima' => 'nullable|integer|min:0|gte:edad_minima',
            'valor_clase' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $nivel->update($request->only([
            'nombre', 'descripcion', 'orden', 'edad_minima', 'edad_maxima', 'valor_clase', 'estado',
        ]));

        return redirect()
            ->route('admin.niveles.index')
            ->with('success', 'Nivel actualizado correctamente');
    }

    public function destroy($id)
    {
        $nivel = Nivel::findOrFail($id);
        $nivel->delete();

        return redirect()
            ->route('admin.niveles.index')
            ->with('success', 'Nivel eliminado correctamente');
    }
}
