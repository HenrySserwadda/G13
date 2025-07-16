<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
             $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('location');
            $table->string('mobile');
            $table->decimal('total', 10, 2);
            $table->timestamps();
             $table->string('status')->default('completed');
        });

    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}; 