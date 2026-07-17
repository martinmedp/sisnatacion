<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['alumnos', 'docentes', 'acudientes', 'administrativos'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->unique()
                    ->constrained('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        foreach (['alumnos', 'docentes', 'acudientes', 'administrativos'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
