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
        Schema::create('fuzzy_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('fuzzy_criteria')->cascadeOnDelete();
            $table->decimal('min_value', 10, 2);
            $table->decimal('mid_value', 10, 2);
            $table->decimal('max_value', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuzzy_memberships');
    }
};
