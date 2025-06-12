<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserCreateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $count;
    public $message;

    public function __construct($count, $message)
    {
        $this->count   = $count;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'count'     => $this->count,
            'title'     => $this->message,
            'sub_title' => 'Tap to view new users',
            'type'      => 'User Registration',
        ];
    }
}
