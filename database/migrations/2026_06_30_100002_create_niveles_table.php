<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->unsignedTinyInteger('edad_minima')->nullable();
            $table->unsignedTinyInteger('edad_maxima')->nullable();
            $table->decimal('valor_clase', 10, 2)->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles');
    }
};
