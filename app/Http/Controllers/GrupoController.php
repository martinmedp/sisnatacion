<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Nivel;
use App\Models\Sede;
use App\Models\Docente;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with(['nivel', 'sede', 'docente', 'horarios'])
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.grupos.index', compact('grupos'));
    }

    public function create()
    {
        $niveles  = Nivel::where('estado', 'activo')->orderBy('orden')->get();
        $sedes    = Sede::where('estado', 'activo')->orderBy('nombre')->get();
        $docentes = Docente::where('estado', 'ACTIVO')->orderBy('nombre_completo')->get();

        return view('admin.grupos.create', compact('niveles', 'sedes', 'docentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'nivel_id'    => 'required|exists:niveles,id',
            'sede_id'     => 'required|exists:sedes,id',
            'docente_id'  => 'required|exists:docentes,id',
            'cupo_maximo' => 'required|integer|min:1|max:100',
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:activo,inactivo',
        ]);

        Grupo::create($request->only([
            'nombre', 'nivel_id', 'sede_id', 'docente_id', 'cupo_maximo', 'descripcion', 'estado',
        ]));

        return redirect()
            ->route('admin.grupos.index')
            ->with('success', 'Grupo creado correctamente');
    }

    public function edit($id)
    {
        $grupo    = Grupo::with('horarios')->findOrFail($id);
        $niveles  = Nivel::where('estado', 'activo')->orderBy('orden')->get();
        $sedes    = Sede::where('estado', 'activo')->orderBy('nombre')->get();
        $docentes = Docente::where('estado', 'ACTIVO')->orderBy('nombre_completo')->get();

        return view('admin.grupos.edit', compact('grupo', 'niveles', 'sedes', 'docentes'));
    }

    public function update(Request $request, $id)
    {
        $grupo = Grupo::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'nivel_id'    => 'required|exists:niveles,id',
            'sede_id'     => 'required|exists:sedes,id',
            'docente_id'  => 'required|exists:docentes,id',
            'cupo_maximo' => 'required|integer|min:1|max:100',
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:activo,inactivo',
        ]);

        $grupo->update($request->only([
            'nombre', 'nivel_id', 'sede_id', 'docente_id', 'cupo_maximo', 'descripcion', 'estado',
        ]));

        return redirect()
            ->route('admin.grupos.index')
            ->with('success', 'Grupo actualizado correctamente');
    }

    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        return redirect()
            ->route('admin.grupos.index')
            ->with('success', 'Grupo eliminado correctamente');
    }
}
