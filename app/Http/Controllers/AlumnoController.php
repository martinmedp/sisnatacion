<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Acudiente;
use App\Services\UsuarioGeneradorService;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $alumnos = Alumno::with('acudiente')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre_completo', 'like', "%{$buscar}%")
                        ->orWhere('codigo', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_completo', 'asc')
            ->get();

        return view('admin.alumnos.index', compact('alumnos', 'buscar'));
    }

    public function create()
    {
        $acudientes = Acudiente::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        return view('admin.alumnos.create', compact('acudientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nombre_completo'      => 'required|string|max:255',
            'tipo_documento'       => 'nullable|string|max:20',
            'numero_documento'     => 'nullable|string|max:30',
            'fecha_nacimiento'     => 'nullable|date',
            'sexo'                 => 'nullable|in:masculino,femenino',
            'telefono'             => 'nullable|string|max:20',
            'direccion'            => 'nullable|string|max:255',
            'correo'               => 'nullable|email|max:255',
            'acudiente_id'         => 'nullable|exists:acudientes,id',
            'contacto_emergencia'  => 'nullable|string|max:255',
            'telefono_emergencia'  => 'nullable|string|max:20',
            'observaciones'        => 'nullable|string',
            'estado'               => 'required|in:activo,inactivo',
        ]);

        $data = $request->only([
            'nombre_completo',
            'tipo_documento',
            'numero_documento',
            'fecha_nacimiento',
            'sexo',
            'telefono',
            'direccion',
            'correo',
            'acudiente_id',
            'contacto_emergencia',
            'telefono_emergencia',
            'observaciones',
            'estado',
        ]);

        if ($request->hasFile('foto')) {
            $archivo = $request->file('foto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/alumnos'), $nombreArchivo);
            $data['foto'] = 'uploads/alumnos/' . $nombreArchivo;
        }

        // Genera (o vincula) su cuenta de acceso si tiene correo
        $resultadoUsuario = UsuarioGeneradorService::generarUsuario(
            $data['nombre_completo'],
            $data['correo'] ?? null,
            'alumno',
            $data['numero_documento'] ?? null
        );

        if ($resultadoUsuario) {
            $data['user_id'] = $resultadoUsuario['user']->id;
        }

        $alumno = Alumno::create($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id'     => $alumno->id,
                'nombre' => $alumno->nombre_completo,
                'codigo' => $alumno->codigo,
            ]);
        }

        $mensaje = 'Alumno guardado correctamente';
        if ($resultadoUsuario && $resultadoUsuario['password_generada']) {
            $mensaje .= '. Se creó su cuenta de acceso: ' . $data['correo']
                . ' / contraseña temporal: ' . $resultadoUsuario['password_generada'];
        }

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', $mensaje);
    }

    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        $acudientes = Acudiente::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        return view('admin.alumnos.edit', compact('alumno', 'acudientes'));
    }

    public function update(Request $request, $id)
    {
        $alumno = Alumno::findOrFail($id);

        $request->validate([
            'foto'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nombre_completo'      => 'required|string|max:255',
            'tipo_documento'       => 'nullable|string|max:20',
            'numero_documento'     => 'nullable|string|max:30',
            'fecha_nacimiento'     => 'nullable|date',
            'sexo'                 => 'nullable|in:masculino,femenino',
            'telefono'             => 'nullable|string|max:20',
            'direccion'            => 'nullable|string|max:255',
            'correo'               => 'nullable|email|max:255',
            'acudiente_id'         => 'nullable|exists:acudientes,id',
            'contacto_emergencia'  => 'nullable|string|max:255',
            'telefono_emergencia'  => 'nullable|string|max:20',
            'observaciones'        => 'nullable|string',
            'estado'               => 'required|in:activo,inactivo',
        ]);

        $data = $request->only([
            'nombre_completo',
            'tipo_documento',
            'numero_documento',
            'fecha_nacimiento',
            'sexo',
            'telefono',
            'direccion',
            'correo',
            'acudiente_id',
            'contacto_emergencia',
            'telefono_emergencia',
            'observaciones',
            'estado',
        ]);

        if ($request->hasFile('foto')) {
            if ($alumno->foto && file_exists(public_path($alumno->foto))) {
                unlink(public_path($alumno->foto));
            }

            $archivo = $request->file('foto');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/alumnos'), $nombreArchivo);
            $data['foto'] = 'uploads/alumnos/' . $nombreArchivo;
        }

        $mensaje = 'Alumno actualizado correctamente';

        // Si aún no tiene cuenta y ahora se le asignó un correo, generarla
        if (!$alumno->user_id && !empty($data['correo'])) {
            $resultadoUsuario = UsuarioGeneradorService::generarUsuario(
                $data['nombre_completo'],
                $data['correo'],
                'alumno',
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

        $alumno->update($data);

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', $mensaje);
    }

    public function destroy($id)
    {
        $alumno = Alumno::findOrFail($id);

        if ($alumno->matriculas()->exists()) {
            return redirect()
                ->route('admin.alumnos.index')
                ->with('error', 'No se puede eliminar a ' . $alumno->nombre_completo . ' porque tiene matrículas registradas (con cuotas y/o pagos asociados). Si ya no está activo, cambia su estado a Inactivo en su lugar.');
        }

        if ($alumno->foto && file_exists(public_path($alumno->foto))) {
            unlink(public_path($alumno->foto));
        }

        $alumno->delete();

        return redirect()
            ->route('admin.alumnos.index')
            ->with('success', 'Alumno eliminado correctamente');
    }
}
