<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acudiente;

class AcudienteController extends Controller
{
    public function index()
    {
        $acudientes = Acudiente::withCount('alumnos')
            ->orderBy('nombre_completo', 'asc')
            ->get();

        return view('admin.acudientes.index', compact('acudientes'));
    }

    public function create()
    {
        return view('admin.acudientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo'  => 'required|string|max:255',
            'tipo_documento'   => 'nullable|string|max:20',
            'numero_documento' => 'nullable|string|max:30',
            'parentesco'       => 'nullable|string|max:50',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email|max:255',
            'direccion'        => 'nullable|string|max:255',
            'observaciones'    => 'nullable|string',
            'estado'           => 'required|in:activo,inactivo',
        ]);

        Acudiente::create($request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'parentesco',
            'telefono', 'correo', 'direccion', 'observaciones', 'estado',
        ]));

        return redirect()
            ->route('admin.acudientes.index')
            ->with('success', 'Acudiente guardado correctamente');
    }

    public function edit($id)
    {
        $acudiente = Acudiente::findOrFail($id);

        return view('admin.acudientes.edit', compact('acudiente'));
    }

    public function update(Request $request, $id)
    {
        $acudiente = Acudiente::findOrFail($id);

        $request->validate([
            'nombre_completo'  => 'required|string|max:255',
            'tipo_documento'   => 'nullable|string|max:20',
            'numero_documento' => 'nullable|string|max:30',
            'parentesco'       => 'nullable|string|max:50',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email|max:255',
            'direccion'        => 'nullable|string|max:255',
            'observaciones'    => 'nullable|string',
            'estado'           => 'required|in:activo,inactivo',
        ]);

        $acudiente->update($request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'parentesco',
            'telefono', 'correo', 'direccion', 'observaciones', 'estado',
        ]));

        return redirect()
            ->route('admin.acudientes.index')
            ->with('success', 'Acudiente actualizado correctamente');
    }

    public function destroy($id)
    {
        $acudiente = Acudiente::findOrFail($id);
        $acudiente->delete();

        return redirect()
            ->route('admin.acudientes.index')
            ->with('success', 'Acudiente eliminado correctamente');
    }
}
