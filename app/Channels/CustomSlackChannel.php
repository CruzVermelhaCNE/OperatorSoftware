<?php
declare(strict_types=1);

namespace App\Channels;

use Illuminate\Notifications\Notification;

class CustomSlackChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toCustomSlack($notifiable);

        // Send notification to the $notifiable instance...
    }
}
