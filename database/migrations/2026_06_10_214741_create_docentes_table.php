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
        Schema::create('docentes', function (Blueprint $table) {

            $table->id();
            // Foto para SPA y administración
            $table->string('foto')->nullable();
            // Información personal
            $table->string('nombre_completo');
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 30)->unique();
            $table->date('fecha_nacimiento')
                ->nullable();
            $table->string('estado_civil', 30)
                ->nullable();
            // Contacto
            $table->string('telefono', 30)
                ->nullable();
            $table->string('direccion', 255)
                ->nullable();
            $table->string('correo_personal')
                ->nullable();
            $table->string('correo_institucional')
                ->nullable();
            // Formación académica
            $table->string('profesion')
                ->nullable();
            $table->string('nivel_academico')
                ->nullable();
            // Información institucional
            $table->string('cargo');
            $table->text('perfil')
                ->nullable();
            $table->date('fecha_ingreso')
                ->nullable();
            // SPA
            $table->integer('orden')
                ->default(1);
            $table->enum('estado', [
                'ACTIVO',
                'INACTIVO'
            ])->default('ACTIVO');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
