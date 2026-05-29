<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_question_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('result_id')->constrained('exam_results')->cascadeOnDelete();
            $table->foreignUuid('exam_question_id')->constrained('exam_questions')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('is_correct')->nullable();
            $table->enum('marking_status', ['auto', 'pending', 'marked'])->default('pending');
            $table->foreignUuid('marked_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('marked_at')->nullable();
            $table->text('marker_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_question_results');
    }
};
