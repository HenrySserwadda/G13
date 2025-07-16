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
        Schema::create('workforce_allocations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('supply_center_id')->constrained()->onDelete('cascade');
    $table->bigInteger('sales');
    $table->integer('stock');
    $table->integer('allocated_workers')->default(0);   // ✅ Add this
    $table->string('status');
    $table->string('recommendation_reason');
    $table->integer('performance_score')->default(0);  // ✅ And this
    $table->timestamps();

            // Add index for better performance
            $table->index('supply_center_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workforce_allocations');
    }
};