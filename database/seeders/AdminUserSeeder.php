<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => 'password',
                'role' => 'admin',
                'citizen_id' => null,
                'is_first_login' => false,
            ]
        );
    }
}