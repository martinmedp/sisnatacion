<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acudiente;
use App\Services\UsuarioGeneradorService;

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

        $data = $request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'parentesco',
            'telefono', 'correo', 'direccion', 'observaciones', 'estado',
        ]);

        $resultadoUsuario = UsuarioGeneradorService::generarUsuario(
            $data['nombre_completo'],
            $data['correo'] ?? null,
            'acudiente',
            $data['numero_documento'] ?? null
        );

        if ($resultadoUsuario) {
            $data['user_id'] = $resultadoUsuario['user']->id;
        }

        $acudiente = Acudiente::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id'     => $acudiente->id,
                'nombre' => $acudiente->nombre_completo,
                'parentesco' => $acudiente->parentesco,
            ]);
        }

        $mensaje = 'Acudiente guardado correctamente';
        if ($resultadoUsuario && $resultadoUsuario['password_generada']) {
            $mensaje .= '. Se creó su cuenta de acceso: ' . $data['correo']
                . ' / contraseña temporal: ' . $resultadoUsuario['password_generada'];
        }

        return redirect()
            ->route('admin.acudientes.index')
            ->with('success', $mensaje);
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

        $data = $request->only([
            'nombre_completo', 'tipo_documento', 'numero_documento', 'parentesco',
            'telefono', 'correo', 'direccion', 'observaciones', 'estado',
        ]);

        $mensaje = 'Acudiente actualizado correctamente';

        if (!$acudiente->user_id && !empty($data['correo'])) {
            $resultadoUsuario = UsuarioGeneradorService::generarUsuario(
                $data['nombre_completo'],
                $data['correo'],
                'acudiente',
                $data['numero_documento'] ?? null
            );

            if ($resultadoUsuario) {
                $data['user_id'] = $resultadoUsuario['user']->id;

                if ($resultadoUsuario['password_generada']) {
                    $mensaje .= '. Se creó su cuenta de acceso: ' . $data['correo']
                        . ' / contraseña temporal: ' . $resultadoUsuario['password_generada'];
                }
            }
        }

        $acudiente->update($data);

        return redirect()
            ->route('admin.acudientes.index')
            ->with('success', $mensaje);
    }

    public function destroy($id)
    {
        $acudiente = Acudiente::findOrFail($id);

        // Si tenía cuenta de acceso vinculada, se borra también — de lo
        // contrario su correo queda "atascado" y no se puede volver a
        // registrar ni asignar a otro acudiente.
        if ($acudiente->user_id) {
            \App\Models\User::where('id', $acudiente->user_id)->delete();
        }

        $acudiente->delete();

        return redirect()
            ->route('admin.acudientes.index')
            ->with('success', 'Acudiente eliminado correctamente');
    }
}
