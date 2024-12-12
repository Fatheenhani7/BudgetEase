<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First update all products to have 'Other' as category
        DB::table('products')->update(['category' => 'Other']);
        
        // Delete existing categories
        DB::table('marketplace_categories')->delete();

        // Insert new categories
        DB::table('marketplace_categories')->insert([
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
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update all products to have 'Other' as category
        DB::table('products')->update(['category' => 'Other']);
        
        // Delete all categories except 'Other'
        DB::table('marketplace_categories')->whereNotIn('name', ['Other'])->delete();
    }
};
