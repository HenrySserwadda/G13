<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chatmessages', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('message');
            $table->string('attachment_type')->nullable()->after('attachment_path');
            $table->string('attachment_name')->nullable()->after('attachment_type');
            $table->unsignedInteger('attachment_size')->nullable()->after('attachment_name');
        });
    }

    public function down()
    {
        Schema::table('chatmessages', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_type', 'attachment_name', 'attachment_size']);
        });
    }
};