<?php

namespace App\Services;

use App\Mail\StaffWelcomeMail;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StaffService
{
    public function create(array $data): Staff
    {
        $school   = School::first();
        $tempPass = Str::upper(Str::random(3)) . rand(100, 999) . Str::random(3);
        $base     = strtolower($data['first_name'] . '.' . $data['last_name']);
        $username = $this->uniqueUsername(preg_replace('/[^a-z0-9._]/', '', $base));

        $user = User::create([
            'school_id' => $school->id,
            'user_type' => 'staff',
            'username'  => $username,
            'password'  => $tempPass,
            'phone'     => $data['phone'] ?? null,
            'is_active' => true,
        ]);

        $staff = Staff::create([
            'school_id'  => $school->id,
            'user_id'    => $user->id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'job_title'  => $data['job_title'] ?? null,
            'email'      => $data['email'] ?? null,
            'phone'      => $data['phone'] ?? null,
            'tsc_number' => $data['tsc_number'] ?? null,
        ]);

        if (! empty($data['email'])) {
            $loginUrl = rtrim(config('app.url'), '/') . '/' . tenant()->id . '/login';
            Mail::to($data['email'])->send(new StaffWelcomeMail(
                schoolName:        $school->name,
                staffName:         $staff->full_name,
                username:          $username,
                temporaryPassword: $tempPass,
                loginUrl:          $loginUrl,
            ));
        }

        return $staff;
    }

    public function update(Staff $staff, array $data): Staff
    {
        $staff->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'job_title'  => $data['job_title'] ?? null,
            'email'      => $data['email'] ?? null,
            'phone'      => $data['phone'] ?? null,
            'tsc_number' => $data['tsc_number'] ?? null,
        ]);

        $staff->user->update([
            'phone'     => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $staff;
    }

    public function resetPassword(Staff $staff): string
    {
        $newPass = Str::upper(Str::random(3)) . rand(100, 999) . Str::random(3);
        $staff->user->update(['password' => $newPass]);

        if ($staff->email) {
            $loginUrl = rtrim(config('app.url'), '/') . '/' . tenant()->id . '/login';
            Mail::to($staff->email)->send(new StaffWelcomeMail(
                schoolName:        School::first()->name,
                staffName:         $staff->full_name,
                username:          $staff->user->username,
                temporaryPassword: $newPass,
                loginUrl:          $loginUrl,
            ));
        }

        return $newPass;
    }

    private function uniqueUsername(string $base): string
    {
        $username = $base;
        $i        = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }
        return $username;
    }
}
