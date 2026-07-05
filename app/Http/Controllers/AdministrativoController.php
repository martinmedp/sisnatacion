<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrativo;
use App\Models\Cargo;
use App\Models\Sede;

class AdministrativoController extends Controller
{
    public function index()
    {
        $administrativos = Administrativo::with(['cargo', 'sede'])
            ->orderBy('nombre_completo', 'asc')
            ->get();

        return view('admin.administrativos.index', compact('administrativos'));
    }

    public function create()
    {
        $cargos = Cargo::where('estado', 'activo')->orderBy('nombre')->get();
        $sedes  = Sede::where('estado', 'activo')->orderBy('nombre')->get();

        return view('admin.administrativos.create', compact('cargos', 'sedes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo'      => 'required|string|max:255',
            'tipo_documento'       => 'nullable|string|max:20',
            'numero_documento'     => 'nullable|string|max:30',
            'fecha_nacimiento'     => 'nullable|date',
            'telefono'             => 'nullable|string|max:20',
            'correo'               => 'nullable|email|max:255',
            'cargo_id'             => 'required|exists:cargos,id',
            'sede_id'              => 'nullable|exists:sedes,id',
            'fecha_ingreso'        => 'nullable|date',
            'contacto_emergencia'  => 'nullable|string|max:255',
            'telefono_emergencia'  => 'nullable|string|max:20',
            'observaciones'        => 'nullable|string',
            'estado'               => 'required|in:activo,inactivo',
        ]);

        Administrativo::create($request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'fecha_nacimiento',
            'telefono', 'correo', 'cargo_id', 'sede_id', 'fecha_ingreso',
            'contacto_emergencia', 'telefono_emergencia', 'observaciones', 'estado',
        ]));

        return redirect()
            ->route('admin.administrativos.index')
            ->with('success', 'Administrativo guardado correctamente');
    }

    public function edit($id)
    {
        $administrativo = Administrativo::findOrFail($id);
        $cargos = Cargo::where('estado', 'activo')->orderBy('nombre')->get();
        $sedes  = Sede::where('estado', 'activo')->orderBy('nombre')->get();

        return view('admin.administrativos.edit', compact('administrativo', 'cargos', 'sedes'));
    }

    public function update(Request $request, $id)
    {
        $administrativo = Administrativo::findOrFail($id);

        $request->validate([
            'nombre_completo'      => 'required|string|max:255',
            'tipo_documento'       => 'nullable|string|max:20',
            'numero_documento'     => 'nullable|string|max:30',
            'fecha_nacimiento'     => 'nullable|date',
            'telefono'             => 'nullable|string|max:20',
            'correo'               => 'nullable|email|max:255',
            'cargo_id'             => 'required|exists:cargos,id',
            'sede_id'              => 'nullable|exists:sedes,id',
            'fecha_ingreso'        => 'nullable|date',
            'contacto_emergencia'  => 'nullable|string|max:255',
            'telefono_emergencia'  => 'nullable|string|max:20',
            'observaciones'        => 'nullable|string',
            'estado'               => 'required|in:activo,inactivo',
        ]);

        $administrativo->update($request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'fecha_nacimiento',
            'telefono', 'correo', 'cargo_id', 'sede_id', 'fecha_ingreso',
            'contacto_emergencia', 'telefono_emergencia', 'observaciones', 'estado',
        ]));

        return redirect()
            ->route('admin.administrativos.index')
            ->with('success', 'Administrativo actualizado correctamente');
    }

    public function destroy($id)
    {
        $administrativo = Administrativo::findOrFail($id);
        $administrativo->delete();

        return redirect()
            ->route('admin.administrativos.index')
            ->with('success', 'Administrativo eliminado correctamente');
    }
}
