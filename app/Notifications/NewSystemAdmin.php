<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
class NewSystemAdmin extends Notification
{
    use Queueable;
    public $user;
    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user=$user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New System Admin')
            ->greeting('Hello, '.$this->user->name)
            ->line('This is to inform you that you have been made a system administrator for our Durabag supply chain management system.')
            ->line('Here is your new user identification number: '.$this->user->user_id)
            ->action('Login', url('/login'));
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
