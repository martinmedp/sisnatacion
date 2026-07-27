<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_atencion_sede', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade');
            $table->enum('dia_semana', [
                'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'
            ]);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            // Una sede solo tiene una configuración de atención por día
            $table->unique(['sede_id', 'dia_semana']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_atencion_sede');
    }
};
