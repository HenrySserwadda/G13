<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('workforce_allocations', function (Blueprint $table) {
        $table->decimal('performance_score', 15, 2)->change(); // Allow large numbers with 2 decimal places
    });
}

public function down(): void
{
    Schema::table('workforce_allocations', function (Blueprint $table) {
        $table->integer('performance_score')->change(); // Or whatever the original type was
    });
}

};
