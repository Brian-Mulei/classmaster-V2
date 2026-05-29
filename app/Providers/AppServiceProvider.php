<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // exam_target polymorphic map — never use raw type strings in business logic
        Relation::morphMap([
            'enrollment' => \App\Models\Enrollment::class,
            'student'    => \App\Models\Student::class,
        ]);

        // Reusable audit columns: call $table->tenantAudit() in any tenant migration
        Blueprint::macro('tenantAudit', function (bool $withSchoolId = true) {
            /** @var Blueprint $this */
            if ($withSchoolId) {
                $this->foreignUuid('school_id')->constrained('schools');
            }
            $this->uuid('created_by')->nullable();
            $this->uuid('updated_by')->nullable();
            $this->boolean('is_active')->default(true)->index();
        });
    }
}
