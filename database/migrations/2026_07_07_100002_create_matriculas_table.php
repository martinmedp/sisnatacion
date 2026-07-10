<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('restrict');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('restrict');
            $table->foreignId('descuento_id')->nullable()->constrained('descuentos')->onDelete('set null');
            $table->date('fecha_matricula');
            $table->decimal('valor_total_nivel', 10, 2);
            $table->decimal('descuento_aplicado', 10, 2)->default(0);
            $table->unsignedTinyInteger('numero_cuotas');
            $table->decimal('valor_cuota', 10, 2);
            $table->enum('estado', ['activa', 'finalizada', 'cancelada'])->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
