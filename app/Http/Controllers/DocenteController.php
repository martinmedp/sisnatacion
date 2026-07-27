<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Services\UsuarioGeneradorService;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $docentes = Docente::when($buscar, function ($query) use ($buscar) {
            $query->where('nombre_completo', 'like', "%{$buscar}%");
        })
            ->orderBy('nombre_completo', 'asc')
            ->get();

        return view('admin.docentes.index', compact('docentes', 'buscar'));
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

        // Genera (o vincula) su cuenta de acceso — prioriza el correo
        // institucional; si no tiene, usa el personal.
        $correoLogin = $request->correo_institucional ?: $request->correo_personal;

        $resultadoUsuario = UsuarioGeneradorService::generarUsuario(
            $request->nombre_completo,
            $correoLogin,
            'docente',
            $request->numero_documento
        );

        $docente = Docente::create([

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

            'user_id' => $resultadoUsuario['user']->id ?? null,

        ]);

        $mensaje = 'Docente creado correctamente';
        if ($resultadoUsuario && $resultadoUsuario['password_generada']) {
            $mensaje .= '. Se creó su cuenta de acceso: ' . $correoLogin
                . ' / contraseña temporal: ' . $resultadoUsuario['password_generada'];
        }

        return redirect()
            ->route('admin.docentes.index')
            ->with(
                'success',
                $mensaje
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

        $mensaje = 'Docente actualizado correctamente';
        $userId = $docente->user_id;

        // Si aún no tiene cuenta y ahora tiene un correo, generarla
        if (!$docente->user_id) {
            $correoLogin = $request->correo_institucional ?: $request->correo_personal;

            $resultadoUsuario = UsuarioGeneradorService::generarUsuario(
                $request->nombre_completo,
                $correoLogin,
                'docente',
                $request->numero_documento
            );

            if ($resultadoUsuario) {
                $userId = $resultadoUsuario['user']->id;

                if ($resultadoUsuario['password_generada']) {
                    $mensaje .= '. Se creó su cuenta de acceso: ' . $correoLogin
                        . ' / contraseña temporal: ' . $resultadoUsuario['password_generada'];
                }
            }
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
            'estado' => $request->estado,

            'user_id' => $userId,

        ]);

        return redirect()
            ->route('admin.docentes.index')
            ->with(
                'success',
                $mensaje
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

    /**
     * Genera una nueva contraseña temporal para la cuenta de acceso
     * de este docente y la muestra una sola vez en pantalla.
     */
    public function restablecerClave($id)
    {
        $docente = Docente::findOrFail($id);

        if (!$docente->user_id) {
            return redirect()
                ->route('admin.docentes.edit', $id)
                ->with('error', 'Este docente no tiene una cuenta de acceso (no tiene correo registrado).');
        }

        $usuario = \App\Models\User::find($docente->user_id);
        $nuevaClave = UsuarioGeneradorService::restablecerClave($usuario, $docente->numero_documento);

        return redirect()
            ->route('admin.docentes.edit', $id)
            ->with('success', 'Contraseña restablecida. Correo: ' . $usuario->email . ' — Nueva clave temporal: ' . $nuevaClave);
    }
}
