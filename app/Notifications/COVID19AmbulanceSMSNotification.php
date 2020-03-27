<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Channels\SMSChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use GuzzleHttp;

class COVID19AmbulanceSMSNotification extends Notification
{
    use Queueable;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $message       = \str_replace('*', '', $message);
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
        return [SMSChannel::class];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SMSMessage
     */
    public function toSMS($notifiable)
    {
        $client = new GuzzleHttp\Client();
        $body = ['Username' =>  env('SMSBUZZ_Username'), 'Password' =>  env('SMSBUZZ_Password')];
        $r = $client->post('https://api.smsbuzz.net/api/accesstoken', [GuzzleHttp\RequestOptions::JSON => $body]);
        $token = json_decode((string) $r->getBody(),true)["AccessToken"];
        $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
        ];
        $body = ['Campaign' => 'default','SenderName' =>  env('SMSBUZZ_SenderName'), 'Destinations' =>  [$notifiable->getFullNumber()], 'Text' => $this->message ];
        $r = $client->post('https://api.smsbuzz.net/sms/send', ['headers' => $headers, GuzzleHttp\RequestOptions::JSON => $body]);
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
