<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `selection` MODIFY `status` ENUM(
            'belum diverifikasi',
            'verifikasi',
            'wawancara',
            'siap di proses',
            'diterima',
            'tidak diterima'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `selection` MODIFY `status` ENUM(
            'verifikasi',
            'wawancara',
            'siap di proses',
            'diterima',
            'tidak diterima'
        ) NOT NULL");
    }
};
