<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::firstOrCreate(
            ['email' => 'admin@classmaster.test'],
            [
                'name'     => 'Super Admin',
                'password' => 'password', // will be hashed by the 'hashed' cast
                'role'     => 'owner',
            ]
        );

        $this->command->info('Super admin: admin@classmaster.test / password');
    }
}
