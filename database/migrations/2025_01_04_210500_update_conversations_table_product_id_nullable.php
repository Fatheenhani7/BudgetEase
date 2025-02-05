<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConversationsTableProductIdNullable extends Migration
{
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
}
