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
        // Drop the existing expenses table first since it has a foreign key to budgets
        Schema::dropIfExists('expenses');
        
        // Drop and recreate the budgets table
        Schema::dropIfExists('budgets');
        
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users_info')->onDelete('cascade');
            $table->string('category_name');
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_spent', 10, 2)->default(0.00);
            $table->timestamps();
        });

        // Recreate the expenses table
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users_info')->onDelete('cascade');
            $table->foreignId('budget_id')->constrained('budgets')->onDelete('cascade');
            $table->string('expense_name');
            $table->decimal('amount', 10, 2);
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('budgets');
    }
};
