<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            // Change the column type
            $table->string('user_id')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            // Re-add the foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
}; 