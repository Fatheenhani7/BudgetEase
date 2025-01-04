<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users_info')->onDelete('cascade');
            $table->integer('rating')->comment('1 to 5 stars');
            $table->text('review')->nullable();
            $table->timestamps();
            
            // Ensure one rating per user per product
            $table->unique(['product_id', 'user_id']);
        });

        // Add average rating column to products table
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->boolean('is_top_seller')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'rating_count', 'is_top_seller']);
        });
        
        Schema::dropIfExists('product_ratings');
    }
};
