<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('super_admin_id')->nullable()->constrained('super_admins')->nullOnDelete();
            // tenant_id is a string slug (stancl/tenancy Tenant model)
            $table->string('tenant_id')->nullable()->index();
            $table->string('action');
            $table->jsonb('details')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_audit_logs');
    }
};
