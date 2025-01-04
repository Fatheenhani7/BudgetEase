<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_info', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_verified_seller')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_status')->default('pending'); // pending, approved, rejected
        });
    }

    public function down(): void
    {
        Schema::table('users_info', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'is_verified_seller', 'verified_at', 'verification_status']);
        });
    }
};
