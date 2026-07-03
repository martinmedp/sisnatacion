<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('nivel_id')->constrained('niveles')->onDelete('restrict');
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('restrict');
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('restrict');
            $table->unsignedTinyInteger('cupo_maximo')->default(15);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
