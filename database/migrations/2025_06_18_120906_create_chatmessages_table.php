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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->foreignId('receiver_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->text('message');
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->string('original_file_name')->nullable();
            $table->timestamps();
            
            // Add indexes for better query performance
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['receiver_id', 'sender_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};