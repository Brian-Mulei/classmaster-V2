<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools');
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            // job_title is HR/display only — never use for authorization
            $table->string('job_title', 80)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('tsc_number', 30)->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
