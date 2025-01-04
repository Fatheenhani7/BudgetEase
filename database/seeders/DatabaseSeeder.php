<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\UserInfo;
use Database\Seeders\MarketplaceCategorySeeder;
use Database\Seeders\AdminSeeder;
=======
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            MarketplaceCategorySeeder::class,
            AdminSeeder::class
=======
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
        ]);
    }
}
