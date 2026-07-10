<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('cobros', function (Blueprint $table) {
      $table->dropColumn(['fecha_pago', 'metodo_pago']);
    });
  }

  public function down(): void
  {
    Schema::table('cobros', function (Blueprint $table) {
      $table->date('fecha_pago')->nullable()->after('estado');
      $table->string('metodo_pago', 30)->nullable()->after('fecha_pago');
    });
  }
};
