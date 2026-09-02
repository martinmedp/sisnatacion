<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE configuracions MODIFY logo TEXT NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE configuracions MODIFY logo TEXT NOT NULL");
    }
};
