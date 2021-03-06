<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Channels\CustomSlackChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TheaterOfOperationsSlackNotification extends Notification
{
    use Queueable;

    private $message = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CustomSlackChannel::class];
    }

    public function toCustomSlack($notifiable)
    {
        $client   = new \GuzzleHttp\Client();
        $response = $client->post(
            env('SLACK_WEBHOOK_URL'),
            [
                \GuzzleHttp\RequestOptions::JSON => [
                    'channel'  => $notifiable->slack_channel,
                    'username' => 'goi',
                    'text'     => $this->message,
                ],
            ]
        );
        return null;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
