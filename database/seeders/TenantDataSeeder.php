<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TenantDataSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = tenant();

        // 1. School record
        $school = School::create([
            'name'  => $tenant->name,
            'level' => $tenant->data['level'] ?? 'mixed',
        ]);

        // 2. Roles & permissions — clear cache so queries hit the fresh DB
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->call(RolesAndPermissionsSeeder::class);

        // 3. Initial admin user — created HERE so roles already exist in same context
        $username = $tenant->id . '-admin';
        $password = Str::upper(Str::random(3)) . rand(100, 999) . Str::random(3);

        $user = User::create([
            'school_id' => $school->id,
            'user_type' => 'staff',
            'username'  => $username,
            'password'  => $password,
            'is_active' => true,
        ]);

        Staff::create([
            'school_id'  => $school->id,
            'user_id'    => $user->id,
            'first_name' => 'School',
            'last_name'  => 'Admin',
            'job_title'  => 'Administrator',
        ]);

        // Assign role directly via DB query — avoids any cache inconsistency
        $role = Role::where('name', 'Head')->where('guard_name', 'web')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        // 4. Stash credentials via VirtualColumn so SchoolCreationService can read them
        $tenant->admin_username  = $username;
        $tenant->admin_temp_pass = $password;  // cleared by service after use
        $tenant->save();
    }
}
