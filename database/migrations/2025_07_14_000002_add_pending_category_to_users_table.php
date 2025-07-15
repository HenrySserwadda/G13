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
        if (!Schema::hasColumn('users', 'pending_category')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('pending_category', ['supplier', 'wholesaler', 'retailer'])->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'pending_category')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('pending_category');
            });
        }
    }
}; 