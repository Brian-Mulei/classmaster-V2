<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('year_report_terms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('year_config_id')->constrained('year_report_configs')->cascadeOnDelete();
            $table->foreignUuid('term_config_id')->constrained('term_report_configs')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->default(1);
            $table->timestamps();

            $table->unique(['year_config_id', 'term_config_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('year_report_terms');
    }
};
