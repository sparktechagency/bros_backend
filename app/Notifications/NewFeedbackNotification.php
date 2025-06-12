<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFeedbackNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public $feedback_id;
    public function __construct($feedback_id)
    {
        $this->feedback_id = $feedback_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
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
    public function toArray(object $notifiable): array
    {
        return [
            'title'       => 'New Feedback',
            'sub_title'   => 'An user just gave a feedback into your service.',
            'type'        => 'New Feedback',
            'feedback_id' => $this->feedback_id,
        ];
    }
}
