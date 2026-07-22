<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReiniciarDatosPrueba extends Command
{
  protected $signature = 'sisnatacion:reiniciar-datos';

  protected $description = 'Borra todos los datos de prueba (alumnos, matrículas, cobros, docentes, etc.), '
    . 'conservando la Configuración del sitio, Banners, Noticias, Galería y el/los usuario(s) admin, '
    . 'y vuelve a cargar los niveles y criterios de evaluación de referencia.';

  /**
   * Tablas de "datos operativos" que se vacían por completo.
   * NO incluye: configuracions, banners, noticias, galerias
   * (contenido del sitio web, se conserva).
   */
  protected array $tablasABorrar = [
    'observador',
    'asistencias',
    'evaluaciones',
    'pagos',
    'cobros',
    'matriculas',
    'horarios',
    'grupos',
    'criterios_evaluacion',
    'niveles',
    'sedes',
    'descuentos',
    'administrativos',
    'acudientes',
    'alumnos',
    'cargos',
    'docentes',
  ];

  public function handle(): int
  {
    $this->warn('⚠️  Esto borrará TODOS los datos de prueba: alumnos, docentes, acudientes,');
    $this->warn('    administrativos, sedes, niveles, grupos, matrículas, cobros, pagos, etc.');
    $this->warn('    Se CONSERVA: Configuración del sitio, Banners, Noticias, Galería y tu usuario admin.');
    $this->newLine();

    if (!$this->confirm('¿Estás seguro de que quieres continuar?', false)) {
      $this->info('Operación cancelada. No se borró nada.');
      return self::SUCCESS;
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // 1. Eliminar usuarios que NO sean admin (y sus roles/permisos asignados)
    $idsAdmin = User::role('admin')->pluck('id');
    $idsABorrar = User::whereNotIn('id', $idsAdmin)->pluck('id');

    if ($idsABorrar->isNotEmpty()) {
      DB::table('model_has_roles')
        ->where('model_type', User::class)
        ->whereIn('model_id', $idsABorrar)
        ->delete();

      DB::table('model_has_permissions')
        ->where('model_type', User::class)
        ->whereIn('model_id', $idsABorrar)
        ->delete();

      User::whereIn('id', $idsABorrar)->delete();

      $this->info("✔ Eliminados {$idsABorrar->count()} usuario(s) no-admin (docentes, alumnos, acudientes, administrativos).");
    }

    // 2. Vaciar las tablas de datos operativos
    foreach ($this->tablasABorrar as $tabla) {
      if (DB::getSchemaBuilder()->hasTable($tabla)) {
        DB::table($tabla)->truncate();
        $this->line("  · Tabla '{$tabla}' vaciada.");
      }
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    $this->newLine();
    $this->info('✅ Datos de prueba eliminados. Configuración, Banners, Noticias, Galería y tu usuario admin quedaron intactos.');

    // 3. Volver a cargar los 6 niveles de referencia y sus criterios de evaluación
    $this->newLine();
    $this->info('Cargando niveles y criterios de evaluación de referencia...');
    $this->call('db:seed', ['--class' => 'NivelesCriteriosSeeder']);

    $this->newLine();
    $this->info('✅ Listo. La base quedó lista para pruebas: niveles y criterios recargados.');

    return self::SUCCESS;
  }
}
