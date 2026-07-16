<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriterioEvaluacion;
use App\Models\Nivel;

class CriterioEvaluacionController extends Controller
{
    public function index(Request $request)
    {
        $nivelId = $request->get('nivel_id');

        $criterios = CriterioEvaluacion::with('nivel')
            ->when($nivelId, fn ($q) => $q->where('nivel_id', $nivelId))
            ->orderBy('nivel_id')
            ->orderBy('orden')
            ->get();

        $niveles = Nivel::orderBy('orden')->get();

        return view('admin.criterios.index', compact('criterios', 'niveles', 'nivelId'));
    }

    public function create()
    {
        $niveles = Nivel::where('estado', 'activo')->orderBy('orden')->get();

        return view('admin.criterios.create', compact('niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nivel_id'    => 'required|exists:niveles,id',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden'       => 'required|integer|min:0',
            'estado'      => 'required|in:activo,inactivo',
        ]);

        CriterioEvaluacion::create($request->only([
            'nivel_id', 'nombre', 'descripcion', 'orden', 'estado',
        ]));

        return redirect()
            ->route('admin.criterios.index')
            ->with('success', 'Criterio de evaluación guardado correctamente');
    }

    public function edit($id)
    {
        $criterio = CriterioEvaluacion::findOrFail($id);
        $niveles = Nivel::where('estado', 'activo')->orderBy('orden')->get();

        return view('admin.criterios.edit', compact('criterio', 'niveles'));
    }

    public function update(Request $request, $id)
    {
        $criterio = CriterioEvaluacion::findOrFail($id);

        $request->validate([
            'nivel_id'    => 'required|exists:niveles,id',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden'       => 'required|integer|min:0',
            'estado'      => 'required|in:activo,inactivo',
        ]);

        $criterio->update($request->only([
            'nivel_id', 'nombre', 'descripcion', 'orden', 'estado',
        ]));

        return redirect()
            ->route('admin.criterios.index')
            ->with('success', 'Criterio de evaluación actualizado correctamente');
    }

    public function destroy($id)
    {
        $criterio = CriterioEvaluacion::findOrFail($id);

        if ($criterio->evaluaciones()->exists()) {
            return redirect()
                ->route('admin.criterios.index')
                ->with('error', 'No se puede eliminar este criterio porque ya tiene evaluaciones registradas. Cámbialo a "Inactivo" en su lugar.');
        }

        $criterio->delete();

        return redirect()
            ->route('admin.criterios.index')
            ->with('success', 'Criterio de evaluación eliminado correctamente');
    }
}
