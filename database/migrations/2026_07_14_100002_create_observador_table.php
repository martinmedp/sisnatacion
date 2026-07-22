<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('docente_id')->nullable()->constrained('docentes')->onDelete('set null');
            $table->enum('tipo', ['comportamiento', 'conducta', 'rendimiento', 'otro'])->default('otro');
            $table->date('fecha');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observador');
    }
};
