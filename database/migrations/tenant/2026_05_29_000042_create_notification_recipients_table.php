<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('notification_id')->constrained('notifications')->cascadeOnDelete();
            $table->enum('recipient_type', ['user', 'external_contact']);
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('external_email')->nullable();
            $table->string('external_phone', 20)->nullable();
            $table->string('external_name')->nullable();
            $table->foreignUuid('linked_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_recipients');
    }
};
