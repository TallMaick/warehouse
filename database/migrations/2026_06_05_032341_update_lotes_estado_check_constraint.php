<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE lotes DROP CONSTRAINT IF EXISTS lotes_estado_check;
            ALTER TABLE lotes ADD CONSTRAINT lotes_estado_check CHECK (estado IN ('disponible', 'en_uso', 'no_disponible'));
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE lotes DROP CONSTRAINT IF EXISTS lotes_estado_check;
            ALTER TABLE lotes ADD CONSTRAINT lotes_estado_check CHECK (estado IN ('disponible'));
        ");
    }
};
