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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
             $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');

    $table->integer('on_hand')->default(0);
    $table->integer('on_order')->default(0);
    
    $table->enum('stock_status', ['in_stock', 'low', 'out'])->default('in_stock');
    $table->enum('delivery_status', ['in_transit', 'received', 'need_to_order', 'in_progress'])->default('received');
    
    $table->date('delivered_on')->nullable();
    $table->date('expected_delivery')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
