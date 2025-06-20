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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
             $table->foreignId('raw_material_id')->constrained()->cascadeOnDelete();
    $table->string('type'); // addition or deduction
    $table->decimal('quantity', 10, 2);
    $table->decimal('new_stock', 10, 2);
    $table->string('reason');
    $table->text('notes')->nullable();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
