<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default budget categories
        $categories = [
            ['name' => 'Living Room', 'description' => 'Living room furniture and decor'],
            ['name' => 'Dining Room', 'description' => 'Dining room furniture and decor'],
            ['name' => 'Kitchen', 'description' => 'Kitchen appliances and essentials'],
            ['name' => 'Bedroom', 'description' => 'Bedroom furniture and decor'],
            ['name' => 'Bathroom', 'description' => 'Bathroom fixtures and accessories'],
            ['name' => 'Office', 'description' => 'Home office furniture and supplies'],
            ['name' => 'Utilities', 'description' => 'Monthly utility bills'],
            ['name' => 'Others', 'description' => 'Other household expenses']
        ];

        DB::table('budget_categories')->insert($categories);
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_categories');
    }
};
