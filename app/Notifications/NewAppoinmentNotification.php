<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppoinmentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $appointment_id;
    public function __construct($appointment_id)
    {
        $this->appointment_id = $appointment_id;
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
            'title'          => 'New Appointment',
            'sub_title'      => 'An user booked an appointment.',
            'type'           => 'New Appointment',
            'appointment_id' => $this->appointment_id
        ];
    }
}
