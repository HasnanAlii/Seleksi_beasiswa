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
        Schema::table('selection', function (Blueprint $table) {
            $table->enum('status', ['verifikasi', 'wawancara', 'siap di proses', 'diterima', 'tidak diterima'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('selection', function (Blueprint $table) {
            $table->enum('status', ['verifikasi', 'wawancara', 'diterima', 'tidak diterima'])->change();
        });
    }
};
