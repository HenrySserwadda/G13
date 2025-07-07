<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raw_material_orders', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('supplier_user_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->string('status')->default('pending');
            $table->timestamps();
             $table->foreign('supplier_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_orders');
    }
};
