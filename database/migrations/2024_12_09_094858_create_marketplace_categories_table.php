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
        Schema::create('marketplace_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Insert default categories
        $categories = [
            ['name' => 'Furniture', 'description' => 'Home and office furniture items'],
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Appliances', 'description' => 'Home and kitchen appliances'],
            ['name' => 'Decor', 'description' => 'Home decoration items'],
            ['name' => 'Books', 'description' => 'Books and educational materials'],
            ['name' => 'Fashion', 'description' => 'Clothing and accessories'],
            ['name' => 'Sports', 'description' => 'Sports equipment and gear'],
            ['name' => 'Others', 'description' => 'Miscellaneous items']
        ];

        DB::table('marketplace_categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_categories');
    }
};
