<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_requirement_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_requirement_value_id')
                ->constrained('application_requirement_values')
                ->name('ard_arv_id_foreign')
                ->cascadeOnDelete();
            $table->string('document_path');
            $table->string('original_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_requirement_documents');
    }
};
