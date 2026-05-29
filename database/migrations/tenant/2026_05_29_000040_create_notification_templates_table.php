<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools');
            $table->string('event_key', 80);
            $table->string('name');
            $table->string('subject_template');
            $table->text('body_template');
            // JSON array of channel strings e.g. ["email","in_app"]
            $table->json('channels');
            $table->enum('default_audience', ['students', 'guardians', 'staff', 'mixed'])->default('mixed');
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['school_id', 'event_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
