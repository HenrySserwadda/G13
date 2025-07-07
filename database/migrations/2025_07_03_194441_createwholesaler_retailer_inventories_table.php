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
        Schema::create('wholesaler_retailer_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Wholesaler or retailer
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(0);
            $table->string('stock_status')->default('in_stock'); // e.g. in_stock, low_stock, out_of_stock
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesaler_retailer_inventories');
    }
};