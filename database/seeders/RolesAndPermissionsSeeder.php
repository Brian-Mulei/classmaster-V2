<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Students
            'students.view', 'students.create', 'students.update', 'students.delete',
            // Staff
            'staff.view', 'staff.create', 'staff.update', 'staff.delete',
            // Enrolments
            'enrolments.view', 'enrolments.create', 'enrolments.update', 'enrolments.delete',
            // Attendance
            'attendance.view', 'attendance.take', 'attendance.edit',
            // Exams
            'exams.view', 'exams.create', 'exams.update', 'exams.delete',
            'exams.mark', 'exams.publish',
            // Reports
            'reports.view', 'reports.generate', 'reports.publish',
            // Notifications
            'notifications.send',
            // Settings (school-level config)
            'settings.view', 'settings.update',
            // Roles & permissions management
            'roles.view', 'roles.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        Role::firstOrCreate(['name' => 'Head'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'Admin'])
            ->syncPermissions(Permission::whereNotIn('name', ['roles.manage'])->get());

        Role::firstOrCreate(['name' => 'Bursar'])
            ->syncPermissions([
                'students.view', 'enrolments.view', 'reports.view', 'notifications.send',
            ]);

        Role::firstOrCreate(['name' => 'Teacher'])
            ->syncPermissions([
                'students.view', 'enrolments.view',
                'attendance.view', 'attendance.take', 'attendance.edit',
                'exams.view', 'exams.mark',
                'reports.view',
            ]);

        Role::firstOrCreate(['name' => 'Student'])
            ->syncPermissions([
                'exams.view', 'reports.view',
            ]);
    }
}
