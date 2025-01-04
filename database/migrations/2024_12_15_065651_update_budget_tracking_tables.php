<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBudgetTrackingTables extends Migration
{
    public function up()
    {
        // Drop foreign keys first
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['budget_id']);
        });

        // Drop and recreate budgets table
        Schema::dropIfExists('budgets');
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users_info')->onDelete('cascade');
            $table->string('category_name');
            $table->decimal('amount_budgeted', 10, 2);
            $table->decimal('amount_spent', 10, 2)->default(0.00);
            $table->timestamp('date_created')->useCurrent();
            $table->timestamps();
        });

        // Drop and recreate expenses table
        Schema::dropIfExists('expenses');
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

    public function down()
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('budgets');
    }
}
