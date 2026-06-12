<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::orderBy('orden')
            ->get();

        return view(
            'admin.docentes.index',
            compact('docentes')
        );
    }

    public function create()
    {
        return view(
            'admin.docentes.create'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|max:255',
            'numero_documento' => 'required|unique:docentes',
            'cargo' => 'required|max:255',
            'foto' => 'nullable|image'
        ]);

        $rutaFoto = null;

        if ($request->hasFile('foto')) {

            $archivo = $request->file('foto');

            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();

            $rutaDestino =
                public_path('uploads/docentes/');

            $archivo->move(
                $rutaDestino,
                $nombreArchivo
            );

            $rutaFoto =
                'uploads/docentes/' .
                $nombreArchivo;
        }

        Docente::create([

            'foto' => $rutaFoto,

            'nombre_completo' => $request->nombre_completo,

            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,

            'fecha_nacimiento' => $request->fecha_nacimiento,
            'estado_civil' => $request->estado_civil,

            'telefono' => $request->telefono,
            'direccion' => $request->direccion,

            'contacto_emergencia' => $request->contacto_emergencia,
            'telefono_emergencia' => $request->telefono_emergencia,

            'correo_personal' => $request->correo_personal,
            'correo_institucional' => $request->correo_institucional,

            'profesion' => $request->profesion,
            'nivel_academico' => $request->nivel_academico,

            'cargo' => $request->cargo,

            'perfil' => $request->perfil,
            'observaciones' => $request->observaciones,

            'fecha_ingreso' => $request->fecha_ingreso,

            'orden' => $request->orden,
            'estado' => $request->estado,

        ]);

        return redirect()
            ->route('admin.docentes.index')
            ->with(
                'success',
                'Docente creado correctamente'
            );
    }

    public function edit($id)
    {
        $docente = Docente::findOrFail($id);

        return view(
            'admin.docentes.edit',
            compact('docente')
        );
    }

    public function update(Request $request, $id)
    {
        $docente = Docente::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|max:255',
            'numero_documento' => 'required|unique:docentes,numero_documento,' . $id,
            'cargo' => 'required|max:255',
            'foto' => 'nullable|image'
        ]);

        $rutaFoto = $docente->foto;

        if ($request->hasFile('foto')) {

            if (
                $docente->foto &&
                file_exists(public_path($docente->foto))
            ) {
                unlink(public_path($docente->foto));
            }

            $archivo = $request->file('foto');

            $nombreArchivo =
                time() . '_' .
                $archivo->getClientOriginalName();

            $archivo->move(
                public_path('uploads/docentes'),
                $nombreArchivo
            );

            $rutaFoto =
                'uploads/docentes/' .
                $nombreArchivo;
        }

        $docente->update([

            'foto' => $rutaFoto,

            'nombre_completo' => $request->nombre_completo,

            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,

            'fecha_nacimiento' => $request->fecha_nacimiento,
            'estado_civil' => $request->estado_civil,

            'telefono' => $request->telefono,
            'direccion' => $request->direccion,

            'contacto_emergencia' => $request->contacto_emergencia,
            'telefono_emergencia' => $request->telefono_emergencia,

            'correo_personal' => $request->correo_personal,
            'correo_institucional' => $request->correo_institucional,

            'profesion' => $request->profesion,
            'nivel_academico' => $request->nivel_academico,

            'cargo' => $request->cargo,

            'perfil' => $request->perfil,
            'observaciones' => $request->observaciones,

            'fecha_ingreso' => $request->fecha_ingreso,

            'orden' => $request->orden,
            'estado' => $request->estado

        ]);

        return redirect()
            ->route('admin.docentes.index')
            ->with(
                'success',
                'Docente actualizado correctamente'
            );
    }

    public function destroy($id)
    {
        $docente = Docente::findOrFail($id);

        if (
            $docente->foto &&
            file_exists(public_path($docente->foto))
        ) {
            unlink(
                public_path($docente->foto)
            );
        }

        $docente->delete();

        return redirect()
            ->route('admin.docentes.index')
            ->with(
                'success',
                'Docente eliminado correctamente'
            );
    }
}
