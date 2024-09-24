<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;


class WeatherAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $precipitation;
    private $uvIndex;

    public function __construct($precipitation, $uvIndex)
    {
        $this->precipitation = $precipitation;
        $this->uvIndex = $uvIndex;
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

        Log::info('Weather alert notification is being sent with precipitation: ' . $this->precipitation . ' and UV Index: ' . $this->uvIndex);

        return (new MailMessage)
                    ->subject('Weather Alert: High Precipitation or UV Index')
                    ->line('There is an upcoming weather anomaly:')
                    ->line('Precipitation: ' . $this->precipitation . ' mm')
                    ->line('UV Index: ' . $this->uvIndex)
                    ->line('Please take necessary precautions.')
                    ->action('Check Weather', url('/'))
                    ->line('Stay safe!');
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
