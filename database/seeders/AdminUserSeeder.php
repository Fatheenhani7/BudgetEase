<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        UserInfo::create([
            'username' => 'Admin',
            'email' => 'adminb@gmail.com',
            'password' => Hash::make('admin1234'),
        ]);
    }
}
