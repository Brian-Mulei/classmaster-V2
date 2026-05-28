<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
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
    }
}
