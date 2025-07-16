<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\User;           // Correct User model
use App\Models\ChatMessage;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     *
     * @param  mixed  $user
     * @param  mixed  $message
     * @return void
     */
    public function __construct(public User $user, public ChatMessage $message)
    {
        //$this->user = $user;
        //$this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->message->receiver_id)];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $avatar = $this->user->avatar ? '/storage/' . $this->user->avatar : $this->user->generateDefaultAvatar();
        \Log::info('Broadcasting avatar', ['avatar' => $avatar, 'user_id' => $this->user->id]);
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,
            'file_path' => $this->message->file_path,
            'file_type' => $this->message->file_type,
            'original_file_name' => $this->message->original_file_name,
            'created_at' => $this->message->created_at ? $this->message->created_at->toDateTimeString() : null,
            'sender' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $avatar,
            ],
        ];
    }
}