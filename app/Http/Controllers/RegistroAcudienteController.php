<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acudiente;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RegistroAcudienteController extends Controller
{
    public function create()
    {
        return view('auth.registro-acudiente');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Datos del acudiente
            'acudiente_nombre'      => 'required|string|max:255',
            'acudiente_documento'   => 'nullable|string|max:30',
            'acudiente_parentesco'  => 'nullable|string|max:50',
            'acudiente_telefono'    => 'required|string|max:20',
            'acudiente_correo'      => 'required|email|max:255|unique:users,email',
            'acudiente_password'    => 'required|string|min:6|confirmed',

            // Datos del alumno
            'alumno_nombre'      => 'required|string|max:255',
            'alumno_documento'   => 'nullable|string|max:30',
            'alumno_nacimiento'  => 'nullable|date',
            'alumno_sexo'        => 'nullable|in:masculino,femenino',
        ], [], [
            'acudiente_password_confirmation' => 'confirmación de contraseña',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Crear el usuario con la clave que él mismo eligió
            $usuario = User::create([
                'name'     => $request->acudiente_nombre,
                'email'    => $request->acudiente_correo,
                'password' => Hash::make($request->acudiente_password),
            ]);

            $rol = Role::firstOrCreate(['name' => 'acudiente']);
            $usuario->assignRole($rol);

            // 2. Crear el Acudiente — queda "inactivo" hasta que el admin lo revise
            $acudiente = Acudiente::create([
                'nombre_completo'  => $request->acudiente_nombre,
                'numero_documento' => $request->acudiente_documento,
                'parentesco'       => $request->acudiente_parentesco,
                'telefono'         => $request->acudiente_telefono,
                'correo'           => $request->acudiente_correo,
                'estado'           => 'inactivo',
                'autorregistro'    => true,
                'user_id'          => $usuario->id,
            ]);

            // 3. Crear el Alumno asociado — también "inactivo" hasta la revisión
            Alumno::create([
                'nombre_completo'  => $request->alumno_nombre,
                'numero_documento' => $request->alumno_documento,
                'fecha_nacimiento' => $request->alumno_nacimiento,
                'sexo'             => $request->alumno_sexo,
                'acudiente_id'     => $acudiente->id,
                'estado'           => 'inactivo',
                'autorregistro'    => true,
            ]);
        });

        return redirect()
            ->route('login')
            ->with('status', '¡Registro recibido! Un administrador revisará tus datos y activará tu cuenta pronto. Te avisaremos por correo.');
    }
}
