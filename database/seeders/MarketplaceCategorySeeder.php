<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarketplaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Delete all existing categories
        DB::table('marketplace_categories')->delete();

        // Insert the furniture categories
        $categories = [
            [
                'name' => 'Living Room',
                'description' => 'Furniture for the living room, such as sofas, coffee tables, and TV stands'
            ],
            [
                'name' => 'Bedroom',
                'description' => 'Furniture for bedrooms, including beds, wardrobes, and dressers'
            ],
            [
                'name' => 'Dining Room',
                'description' => 'Furniture for dining rooms, such as dining tables and chairs'
            ],
            [
                'name' => 'Office',
                'description' => 'Office furniture, including desks, chairs, and storage solutions'
            ],
            [
                'name' => 'Outdoor',
                'description' => 'Outdoor furniture, such as patio sets and garden benches'
            ],
            [
                'name' => 'Other',
                'description' => 'Miscellaneous furniture items'
            ]
        ];

        foreach ($categories as $category) {
            DB::table('marketplace_categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
