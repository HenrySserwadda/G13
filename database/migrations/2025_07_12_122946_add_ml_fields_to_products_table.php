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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_ml_generated')->default(false);
            $table->string('ml_style')->nullable();
            $table->string('ml_color')->nullable();
            $table->string('ml_gender')->nullable();
            $table->timestamp('ml_expires_at')->nullable();
            $table->integer('ml_popularity_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_ml_generated',
                'ml_style',
                'ml_color', 
                'ml_gender',
                'ml_expires_at',
                'ml_popularity_score'
            ]);
        });
    }
};
