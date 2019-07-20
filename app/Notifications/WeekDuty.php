<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeekDuty extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $username = env('NOTIFIER_USERNAME', '当番通知くん');

        return (new SlackMessage())
            ->from($username, ':red_circle:')
            ->to(env('TARGET_SLACK_CHANNEL_ID'))
            ->content('今週の当番は <@UA5URN0QM> です');
    }
}
