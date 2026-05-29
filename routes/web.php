<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Central\Dashboard;
use App\Livewire\Central\Schools\Create as SchoolCreate;
use App\Livewire\Central\Schools\Index as SchoolIndex;
use Illuminate\Support\Facades\Route;

// ── Central domain routes (classmaster.test) ─────────────────────────────────

Route::get('/', fn () => redirect()->route('central.login'));

// Guest-only
Route::middleware('guest:super_admin')->group(function () {
    Route::get('/login',           Login::class)->name('central.login');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Authenticated
Route::post('/logout', function () {
    auth('super_admin')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('central.login');
})->name('central.logout');

Route::middleware('auth:super_admin')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('central.dashboard');
    Route::get('/schools', SchoolIndex::class)->name('central.schools.index');
    Route::get('/schools/create', SchoolCreate::class)->name('central.schools.create');
});
