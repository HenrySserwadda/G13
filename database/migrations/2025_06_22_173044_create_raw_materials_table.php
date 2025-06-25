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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
               $table->string('name')->unique();
            $table->string('type')->nullable();
          //  $table->string('unit')->nullable(); // e.g. kg, meters
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->nullable();

            // Foreign key to suppliers table
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');

            // Foreign key to users table (who added the material)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
