<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioGeneradorService
{
    /**
     * Crea (o reutiliza) una cuenta de acceso para una persona
     * (alumno, docente, acudiente o administrativo) según su correo.
     *
     * - Si el correo viene vacío, no hace nada (retorna null).
     * - Si ya existe un usuario con ese correo, lo reutiliza y solo
     *   se asegura de que tenga el rol correcto (no cambia su clave).
     * - Si no existe, lo crea con una contraseña temporal (el número
     *   de documento de la persona, o una aleatoria si no hay documento)
     *   y le asigna el rol correspondiente.
     * - Garantiza que el rol tenga el permiso "{rol}.acceso", necesario
     *   para que el ítem "Mi Panel" aparezca en el menú de ese rol,
     *   sin depender de que el seeder de roles se haya ejecutado.
     *
     * @return array{user: User, password_generada: ?string}|null
     */
    public static function generarUsuario(?string $nombre, ?string $correo, string $rol, ?string $documento = null): ?array
    {
        if (empty($correo)) {
            return null;
        }

        $rolObj = Role::firstOrCreate(['name' => $rol]);

        $permisoAcceso = Permission::firstOrCreate(['name' => $rol . '.acceso']);
        if (!$rolObj->hasPermissionTo($permisoAcceso)) {
            $rolObj->givePermissionTo($permisoAcceso);
        }

        $usuarioExistente = User::where('email', $correo)->first();

        if ($usuarioExistente) {
            if (!$usuarioExistente->hasRole($rol)) {
                $usuarioExistente->assignRole($rolObj);
            }

            return [
                'user'              => $usuarioExistente,
                'password_generada' => null,
            ];
        }

        $passwordPlano = ($documento && strlen($documento) >= 4)
            ? $documento
            : Str::random(8);

        $usuario = User::create([
            'name'     => $nombre ?? $correo,
            'email'    => $correo,
            'password' => Hash::make($passwordPlano),
        ]);

        $usuario->assignRole($rolObj);

        return [
            'user'              => $usuario,
            'password_generada' => $passwordPlano,
        ];
    }
}
