<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_report_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('config_id')->constrained('term_report_configs')->cascadeOnDelete();
            $table->foreignUuid('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->default(1);
            $table->timestamps();

            $table->unique(['config_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_report_exams');
    }
};
