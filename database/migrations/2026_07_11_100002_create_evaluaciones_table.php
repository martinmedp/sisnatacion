<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->onDelete('cascade');
            $table->foreignId('criterio_id')->constrained('criterios_evaluacion')->onDelete('cascade');
            $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('set null');
            $table->enum('estado_criterio', ['no_logrado', 'en_proceso', 'logrado'])->default('no_logrado');
            $table->date('fecha_evaluacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Un mismo criterio no se evalúa dos veces dentro de la misma matrícula
            $table->unique(['matricula_id', 'criterio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
