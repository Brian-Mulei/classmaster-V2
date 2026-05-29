<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_report_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools');
            $table->foreignUuid('term_id')->constrained('terms')->cascadeOnDelete();
            $table->foreignUuid('grade_level_id')->constrained('grade_levels')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('strength_threshold', 5, 2)->default(70);
            $table->decimal('weakness_threshold', 5, 2)->default(40);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_report_configs');
    }
};
