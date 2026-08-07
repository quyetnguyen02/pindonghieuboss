<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // create or update admin user Hieu
        User::updateOrCreate(
            ['name' => 'Hieu'],
            ['email' => 'admin@example.com', 'password' => Hash::make('hieu')]
        );
    }
}
