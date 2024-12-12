<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketplaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        ] as $category) {
            DB::table('marketplace_categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
