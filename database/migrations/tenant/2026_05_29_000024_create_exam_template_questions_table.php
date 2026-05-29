<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_template_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('template_id')->constrained('exam_templates')->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained('questions')->cascadeOnDelete();
            $table->decimal('marks', 5, 2);
            $table->unsignedSmallInteger('sequence');
            $table->timestamps();

            $table->unique(['template_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_template_questions');
    }
};
