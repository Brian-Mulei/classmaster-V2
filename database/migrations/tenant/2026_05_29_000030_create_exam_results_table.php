<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->decimal('auto_score', 7, 2)->nullable();
            $table->decimal('manual_score', 7, 2)->nullable();
            $table->decimal('total_score', 7, 2)->nullable();
            $table->decimal('max_score', 7, 2);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->enum('status', ['pending_marking', 'partially_marked', 'finalised'])->default('pending_marking');
            $table->timestamp('computed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
