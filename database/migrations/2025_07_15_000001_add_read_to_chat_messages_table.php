<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up()
{
    Schema::table('chat_messages', function (Blueprint $table) {
        $table->boolean('read')->default(false)->after('receiver_id');
    });
}

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('read');
        });
    }
}; 