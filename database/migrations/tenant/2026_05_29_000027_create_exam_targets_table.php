<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Polymorphic: target_type ∈ {enrollment, student} via morphMap in AppServiceProvider
        Schema::create('exam_targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->string('target_type');
            $table->uuid('target_id');
            $table->timestamps();

            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_targets');
    }
};
