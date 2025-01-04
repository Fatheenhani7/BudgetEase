<?php

namespace Database\Seeders;

use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        UserInfo::firstOrCreate(
            ['email' => 'adminb@gmail.com'],
            [
                'username' => 'Admin',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_verified_seller' => true,
                'verification_status' => 'approved'
            ]
        );
    }
}
