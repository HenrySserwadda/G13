<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    if (!Schema::hasColumn('orders', 'status')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('total');
        });
    }
}

public function down()
{
    if (Schema::hasColumn('orders', 'status')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

    /**
     * Reverse the migrations.
     */

};
