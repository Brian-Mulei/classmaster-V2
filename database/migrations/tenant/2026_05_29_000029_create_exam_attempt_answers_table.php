<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempt_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignUuid('exam_question_id')->constrained('exam_questions')->cascadeOnDelete();
            // MCQ / true-false answer
            $table->foreignUuid('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            // Short answer / essay
            $table->text('text_answer')->nullable();
            // Math workings image
            $table->string('image_url')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempt_answers');
    }
};
