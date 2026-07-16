<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pagos MODIFY metodo_pago ENUM('efectivo', 'transferencia', 'otro', 'nota_debito')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pagos MODIFY metodo_pago ENUM('efectivo', 'transferencia', 'otro')");
    }
};
