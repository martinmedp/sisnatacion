<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class OtorgarPermisoConfiguracion extends Command
{
  protected $signature = 'sisnatacion:otorgar-permiso-configuracion {email}';

  protected $description = 'Otorga el permiso especial "configuracion.editar" a un usuario específico '
    . '(por su correo), sin necesidad de cambiar su rol. Solo quien tenga este permiso podrá ver y '
    . 'editar la Configuración del sitio (nombre, logo, colores, etc.), aunque tenga rol de admin.';

  public function handle(): int
  {
    $email = $this->argument('email');

    $usuario = User::where('email', $email)->first();

    if (!$usuario) {
      $this->error("No existe ningún usuario con el correo: {$email}");
      return self::FAILURE;
    }

    Permission::firstOrCreate(['name' => 'configuracion.editar']);

    if ($usuario->hasPermissionTo('configuracion.editar')) {
      $this->warn("{$usuario->name} ({$email}) ya tenía este permiso.");
      return self::SUCCESS;
    }

    $usuario->givePermissionTo('configuracion.editar');

    $this->info("✅ Permiso 'configuracion.editar' otorgado a {$usuario->name} ({$email}).");
    $this->line('Ahora esta cuenta (y solo esta, además de las que se agreguen igual) puede ver y editar Configuración.');

    return self::SUCCESS;
  }
}
