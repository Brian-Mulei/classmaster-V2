<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools');
            $table->foreignUuid('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignUuid('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->enum('question_type', [
                'mcq_single', 'mcq_multi', 'true_false',
                'short_answer', 'essay', 'math_workings',
            ]);
            $table->enum('marking_mode', ['auto', 'manual'])->default('auto');
            $table->text('prompt');
            $table->string('media_url')->nullable();
            $table->text('model_answer')->nullable();
            $table->text('marking_rubric')->nullable();
            $table->decimal('default_marks', 5, 2)->default(1);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->unsignedSmallInteger('version')->default(1);
            // self-referencing — no DB FK (can't enforce inline on new schema); handled by Eloquent
            $table->uuid('parent_question_id')->nullable()->index();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
