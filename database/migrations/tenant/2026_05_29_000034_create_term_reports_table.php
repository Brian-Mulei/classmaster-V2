<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools');
            $table->foreignUuid('config_id')->constrained('term_report_configs')->cascadeOnDelete();
            $table->foreignUuid('student_enrollment_id')->constrained('student_enrollments')->cascadeOnDelete();
            $table->decimal('total_marks', 7, 2)->nullable();
            $table->decimal('total_max', 7, 2)->nullable();
            $table->decimal('mean_percentage', 5, 2)->nullable();
            $table->string('mean_grade', 10)->nullable();
            $table->decimal('mean_points', 5, 2)->nullable();
            $table->unsignedSmallInteger('stream_position')->nullable();
            $table->unsignedSmallInteger('class_position')->nullable();
            $table->unsignedSmallInteger('term_position')->nullable();
            $table->decimal('overall_percentile', 5, 2)->nullable();
            $table->foreignUuid('best_subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignUuid('worst_subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->unsignedSmallInteger('days_present')->nullable();
            $table->unsignedSmallInteger('days_absent')->nullable();
            $table->unsignedSmallInteger('days_late')->nullable();
            $table->text('class_teacher_comment')->nullable();
            $table->text('head_teacher_comment')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['config_id', 'student_enrollment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_reports');
    }
};
