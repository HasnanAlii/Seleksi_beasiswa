<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_requirement_values', function (Blueprint $table) {
            // 0 = belum divalidasi, 1 = valid/disetujui, 2 = tidak valid/ditolak
            $table->tinyInteger('validation_status')->default(0)->after('document_path');
            $table->text('validation_notes')->nullable()->after('validation_status');
            $table->timestamp('validated_at')->nullable()->after('validation_notes');
        });
    }

    public function down(): void
    {
        Schema::table('application_requirement_values', function (Blueprint $table) {
            $table->dropColumn(['validation_status', 'validation_notes', 'validated_at']);
        });
    }
};
