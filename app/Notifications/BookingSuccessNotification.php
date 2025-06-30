<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */public $booking_id, $service_name, $service_type;
    public function __construct($booking_id, $service_name, $service_type)
    {
        $this->booking_id   = $booking_id;
        $this->service_name = $service_name;
        $this->service_type = $service_type;
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
            'title'        => 'FULL CIRCLE are ready to clean your car.',
            'sub_title'    => 'Booking successful',
            'type'         => 'Booking successful',
            'booking_id'   => $this->booking_id,
            'service_name' => $this->service_name,
            'service_type' => $this->service_type,
        ];
    }
}
