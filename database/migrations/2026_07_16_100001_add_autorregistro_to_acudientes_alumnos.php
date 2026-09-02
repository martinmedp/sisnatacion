<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acudientes', function (Blueprint $table) {
            $table->boolean('autorregistro')->default(false)->after('estado');
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->boolean('autorregistro')->default(false)->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('acudientes', function (Blueprint $table) {
            $table->dropColumn('autorregistro');
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn('autorregistro');
        });
    }
};
