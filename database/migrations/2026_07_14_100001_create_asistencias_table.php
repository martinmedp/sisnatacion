<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->onDelete('cascade');
            $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('set null');
            $table->date('fecha');
            $table->enum('estado', ['presente', 'ausente', 'tarde', 'excusa'])->default('presente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Un alumno solo tiene un registro de asistencia por día
            $table->unique(['matricula_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
