<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE cobros MODIFY estado ENUM('pendiente', 'parcial', 'pagado', 'vencido') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cobros MODIFY estado ENUM('pendiente', 'pagado', 'vencido') DEFAULT 'pendiente'");
    }
};
