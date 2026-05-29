<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_report_subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('term_report_id')->constrained('term_reports')->cascadeOnDelete();
            $table->foreignUuid('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->decimal('marks_obtained', 7, 2)->nullable();
            $table->decimal('marks_max', 7, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade_label', 10)->nullable();
            $table->decimal('points', 5, 2)->nullable();
            $table->unsignedSmallInteger('stream_position')->nullable();
            $table->unsignedSmallInteger('class_position')->nullable();
            $table->decimal('stream_percentile', 5, 2)->nullable();
            $table->decimal('class_percentile', 5, 2)->nullable();
            $table->enum('performance_label', ['strength', 'neutral', 'weakness'])->nullable();
            $table->text('subject_teacher_comment')->nullable();
            $table->timestamps();

            $table->unique(['term_report_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_report_subjects');
    }
};
