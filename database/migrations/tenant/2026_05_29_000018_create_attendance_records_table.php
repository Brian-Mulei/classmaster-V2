<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools');
            $table->foreignUuid('register_id')->constrained('attendance_registers')->cascadeOnDelete();
            $table->foreignUuid('student_enrollment_id')->constrained('student_enrollments')->cascadeOnDelete();
            $table->enum('status', ['present', 'absent', 'late', 'excused', 'sick']);
            $table->string('remarks')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['register_id', 'student_enrollment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
