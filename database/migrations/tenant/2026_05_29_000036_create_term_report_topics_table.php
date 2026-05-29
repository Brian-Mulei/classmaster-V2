<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_report_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('term_report_subject_id')->constrained('term_report_subjects')->cascadeOnDelete();
            $table->foreignUuid('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->unsignedSmallInteger('questions_attempted')->default(0);
            $table->unsignedSmallInteger('questions_correct')->default(0);
            $table->decimal('marks_obtained', 5, 2)->nullable();
            $table->decimal('marks_max', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->enum('performance_label', ['strength', 'neutral', 'weakness'])->nullable();
            $table->timestamps();

            $table->unique(['term_report_subject_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_report_topics');
    }
};
