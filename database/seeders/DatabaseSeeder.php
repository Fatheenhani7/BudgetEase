<?php

namespace Database\Seeders;

use App\Models\UserInfo;
use Database\Seeders\MarketplaceCategorySeeder;
use Database\Seeders\AdminSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MarketplaceCategorySeeder::class,
            AdminSeeder::class
        ]);
    }
}
