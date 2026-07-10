<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('niveles', function (Blueprint $table) {
            $table->unsignedTinyInteger('duracion_meses')->default(1)->after('valor_clase');
        });
    }

    public function down(): void
    {
        Schema::table('niveles', function (Blueprint $table) {
            $table->dropColumn('duracion_meses');
        });
    }
};
