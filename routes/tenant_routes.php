<?php

declare(strict_types=1);

use App\Livewire\Tenant\Academic\AcademicCalendar;
use App\Livewire\Tenant\Academic\Curriculum;
use App\Livewire\Tenant\Academic\GradeStructure;
use App\Livewire\Tenant\Academic\GradingScales;
use App\Livewire\Tenant\Auth\Login;
use App\Livewire\Tenant\Dashboard;
use App\Livewire\Tenant\Enrollment\Enrollments;
use App\Livewire\Tenant\People\StaffMembers;
use App\Livewire\Tenant\People\Students;
use Illuminate\Support\Facades\Route;

// ── Auth ─────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('tenant.login');
});

Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect(tenant_url('login'));
})->name('tenant.logout');

// ── Authenticated tenant app ──────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/',          Dashboard::class)->name('tenant.home');
    Route::get('/dashboard', Dashboard::class)->name('tenant.dashboard');

    // Academic structure
    Route::prefix('academic')->group(function () {
        Route::get('/grading',    GradingScales::class)->name('tenant.academic.grading');
        Route::get('/curriculum', Curriculum::class)->name('tenant.academic.curriculum');
        Route::get('/classes',    GradeStructure::class)->name('tenant.academic.classes');
        Route::get('/calendar',   AcademicCalendar::class)->name('tenant.academic.calendar');
    });

    // People
    Route::get('/students', Students::class)->name('tenant.students');
    Route::get('/staff',    StaffMembers::class)->name('tenant.staff');

    // Enrollment
    Route::get('/enrollment', Enrollments::class)->name('tenant.enrollment');
});
