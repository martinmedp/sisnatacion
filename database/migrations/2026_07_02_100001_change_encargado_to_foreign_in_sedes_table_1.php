<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('sedes', function (Blueprint $table) {
      $table->dropColumn('encargado');
    });

    Schema::table('sedes', function (Blueprint $table) {
      $table->foreignId('encargado_id')
        ->nullable()
        ->after('telefono')
        ->constrained('docentes')
        ->onDelete('set null');
    });
  }

  public function down(): void
  {
    Schema::table('sedes', function (Blueprint $table) {
      $table->dropForeign(['encargado_id']);
      $table->dropColumn('encargado_id');
    });

    Schema::table('sedes', function (Blueprint $table) {
      $table->string('encargado')->nullable();
    });
  }
};
