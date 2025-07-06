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
        Schema::create('raw_material_order_items', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('raw_material_order_id');
            $table->unsignedBigInteger('raw_material_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
             $table->foreign('raw_material_order_id')->references('id')->on('raw_material_orders')->onDelete('cascade');
            $table->foreign('raw_material_id')->references('id')->on('raw_materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_order_items');
    }
};
