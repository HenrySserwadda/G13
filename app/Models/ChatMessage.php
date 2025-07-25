<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // Add this import

class ChatMessage extends Model  // Class name should be PascalCase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chat_messages';  // Corrected table name to match migration
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'file_path',
        'file_type',
        'original_file_name',
        'read', // Add this line
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}