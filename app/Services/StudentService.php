<?php

namespace App\Services;

use App\Mail\StaffWelcomeMail;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentService
{
    public function create(array $data): Student
    {
        $school   = School::first();
        $tempPass = Str::upper(Str::random(3)) . rand(100, 999) . Str::random(3);
        $username = $this->uniqueUsername(strtolower(preg_replace('/\s+/', '.', trim($data['student_number']))));

        $user = User::create([
            'school_id' => $school->id,
            'user_type' => 'student',
            'username'  => $username,
            'password'  => $tempPass,
            'is_active' => true,
        ]);

        $student = Student::create([
            'school_id'             => $school->id,
            'user_id'               => $user->id,
            'first_name'            => $data['first_name'],
            'middle_name'           => $data['middle_name'] ?? null,
            'last_name'             => $data['last_name'],
            'student_number'        => $data['student_number'],
            'date_of_birth'         => $data['date_of_birth'] ?? null,
            'gender'                => $data['gender'] ?? null,
            'admission_date'        => $data['admission_date'] ?? now(),
            'guardian_name'         => $data['guardian_name'] ?? null,
            'guardian_phone'        => $data['guardian_phone'] ?? null,
            'guardian_email'        => $data['guardian_email'] ?? null,
            'guardian_relationship' => $data['guardian_relationship'] ?? null,
        ]);

        // Email guardian with student login credentials
        if (! empty($data['guardian_email'])) {
            Mail::to($data['guardian_email'])->send(new StaffWelcomeMail(
                schoolName:        $school->name,
                staffName:         $student->full_name,
                username:          $username,
                temporaryPassword: $tempPass,
                loginUrl:          rtrim(config('app.url'), '/') . '/' . tenant()->id . '/login',
            ));
        }

        return $student;
    }

    public function update(Student $student, array $data): Student
    {
        $student->update([
            'first_name'            => $data['first_name'],
            'middle_name'           => $data['middle_name'] ?? null,
            'last_name'             => $data['last_name'],
            'student_number'        => $data['student_number'],
            'date_of_birth'         => $data['date_of_birth'] ?? null,
            'gender'                => $data['gender'] ?? null,
            'guardian_name'         => $data['guardian_name'] ?? null,
            'guardian_phone'        => $data['guardian_phone'] ?? null,
            'guardian_email'        => $data['guardian_email'] ?? null,
            'guardian_relationship' => $data['guardian_relationship'] ?? null,
        ]);

        return $student;
    }

    public function resetPassword(Student $student): string
    {
        $newPass = Str::upper(Str::random(3)) . rand(100, 999) . Str::random(3);
        $student->user->update(['password' => $newPass]);
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
