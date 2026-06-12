<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('docentes', function (Blueprint $table) {

            $table->string('contacto_emergencia')
                ->nullable()
                ->after('telefono');

            $table->string('telefono_emergencia')
                ->nullable()
                ->after('contacto_emergencia');

            $table->text('observaciones')
                ->nullable()
                ->after('perfil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docentes', function (Blueprint $table) {

            $table->dropColumn([
                'contacto_emergencia',
                'telefono_emergencia',
                'observaciones'
            ]);
        });
    }
};
