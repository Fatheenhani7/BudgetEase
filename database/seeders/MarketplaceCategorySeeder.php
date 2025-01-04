<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

class MarketplaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Delete all existing categories
        DB::table('marketplace_categories')->delete();

        // Insert the furniture categories
        $categories = [
=======
        // First, delete existing categories that are not in our new list
        $keepCategories = [
            'Living Room',
            'Bedroom',
            'Dining Room',
            'Office',
            'Outdoor',
            'Other'
        ];
        
        DB::table('marketplace_categories')
            ->whereNotIn('name', $keepCategories)
            ->delete();

        // Insert or update the furniture categories
        foreach ([
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
<<<<<<< HEAD
        ];

        foreach ($categories as $category) {
=======
        ] as $category) {
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
            DB::table('marketplace_categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }
<<<<<<< HEAD

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }
}
