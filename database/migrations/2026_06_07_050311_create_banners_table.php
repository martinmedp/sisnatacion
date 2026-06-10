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
        Schema::create('banners', function (Blueprint $table) {

            $table->id();
            $table->string('titulo', 255);
            $table->string('subtitulo', 500)->nullable();
            $table->string('imagen');
            $table->string('texto_boton', 100)->nullable();
            $table->string('url_boton')->nullable();
            $table->integer('orden')->default(1);
            $table->enum('estado', ['ACTIVO', 'INACTIVO'])
                ->default('ACTIVO');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
