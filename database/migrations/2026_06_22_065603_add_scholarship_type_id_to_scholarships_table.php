<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            // Hapus kolom lama (varchar)
            $table->dropColumn('scholarship_type');

            // Tambah kolom FK ke scholarship_types
            $table->foreignId('scholarship_type_id')
                ->nullable()
                ->after('scholarship_name')
                ->constrained('scholarship_types')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropForeign(['scholarship_type_id']);
            $table->dropColumn('scholarship_type_id');
            $table->string('scholarship_type', 50)->after('scholarship_name');
        });
    }
};
