<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrativo;
use App\Models\Cargo;
use App\Models\Sede;

class AdministrativoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $administrativos = Administrativo::with(['cargo', 'sede'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre_completo', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre_completo', 'asc')
            ->get();

        return view('admin.administrativos.index', compact('administrativos', 'buscar'));
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
            'foto'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        $data = $request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'fecha_nacimiento',
            'telefono', 'correo', 'cargo_id', 'sede_id', 'fecha_ingreso',
            'contacto_emergencia', 'telefono_emergencia', 'observaciones', 'estado',
        ]);

        if ($request->hasFile('foto')) {
            $archivo = $request->file('foto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/administrativos'), $nombreArchivo);
            $data['foto'] = 'uploads/administrativos/' . $nombreArchivo;
        }

        Administrativo::create($data);

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
            'foto'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        $data = $request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'fecha_nacimiento',
            'telefono', 'correo', 'cargo_id', 'sede_id', 'fecha_ingreso',
            'contacto_emergencia', 'telefono_emergencia', 'observaciones', 'estado',
        ]);

        if ($request->hasFile('foto')) {
            if ($administrativo->foto && file_exists(public_path($administrativo->foto))) {
                unlink(public_path($administrativo->foto));
            }

            $archivo = $request->file('foto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/administrativos'), $nombreArchivo);
            $data['foto'] = 'uploads/administrativos/' . $nombreArchivo;
        }

        $administrativo->update($data);

        return redirect()
            ->route('admin.administrativos.index')
            ->with('success', 'Administrativo actualizado correctamente');
    }

    public function destroy($id)
    {
        $administrativo = Administrativo::findOrFail($id);

        if ($administrativo->foto && file_exists(public_path($administrativo->foto))) {
            unlink(public_path($administrativo->foto));
        }

        $administrativo->delete();

        return redirect()
            ->route('admin.administrativos.index')
            ->with('success', 'Administrativo eliminado correctamente');
    }
}
