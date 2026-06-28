<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
  public function run(): void
  {
    // Limpiar caché de permisos
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // =====================================================
    // PERMISOS
    // =====================================================

    // Admin
    Permission::create(['name' => 'admin.acceso']);

    // Docente
    Permission::create(['name' => 'docente.acceso']);
    Permission::create(['name' => 'docente.ver-clases']);
    Permission::create(['name' => 'docente.ver-alumnos']);
    Permission::create(['name' => 'docente.registrar-asistencia']);

    // Alumno
    Permission::create(['name' => 'alumno.acceso']);
    Permission::create(['name' => 'alumno.ver-horario']);
    Permission::create(['name' => 'alumno.ver-pagos']);
    Permission::create(['name' => 'alumno.ver-asistencia']);

    // Acudiente
    Permission::create(['name' => 'acudiente.acceso']);
    Permission::create(['name' => 'acudiente.ver-hijo']);
    Permission::create(['name' => 'acudiente.ver-pagos']);
    Permission::create(['name' => 'acudiente.ver-horario']);

    // =====================================================
    // ROLES
    // =====================================================

    $admin = Role::create(['name' => 'admin']);
    $admin->givePermissionTo(Permission::all());

    $docente = Role::create(['name' => 'docente']);
    $docente->givePermissionTo([
      'docente.acceso',
      'docente.ver-clases',
      'docente.ver-alumnos',
      'docente.registrar-asistencia',
    ]);

    $alumno = Role::create(['name' => 'alumno']);
    $alumno->givePermissionTo([
      'alumno.acceso',
      'alumno.ver-horario',
      'alumno.ver-pagos',
      'alumno.ver-asistencia',
    ]);

    $acudiente = Role::create(['name' => 'acudiente']);
    $acudiente->givePermissionTo([
      'acudiente.acceso',
      'acudiente.ver-hijo',
      'acudiente.ver-pagos',
      'acudiente.ver-horario',
    ]);

    // =====================================================
    // USUARIO ADMIN POR DEFECTO
    // =====================================================

    $adminUser = User::firstOrCreate(
      ['email' => 'admin@sisnatacion.com'],
      [
        'name'     => 'Administrador',
        'password' => Hash::make('Admin2026*'),
      ]
    );
    $adminUser->assignRole('admin');
  }
}
