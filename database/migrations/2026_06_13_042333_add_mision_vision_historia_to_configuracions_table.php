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
        Schema::table('configuracions', function (Blueprint $table) {
            $table->text('mision')
                ->nullable()
                ->after('descripcion');
            $table->text('vision')
                ->nullable()
                ->after('mision');
            $table->longText('historia')
                ->nullable()
                ->after('vision');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracions', function (Blueprint $table) {
            $table->dropColumn([
                'mision',
                'vision',
                'historia'
            ]);
        });
    }
};
