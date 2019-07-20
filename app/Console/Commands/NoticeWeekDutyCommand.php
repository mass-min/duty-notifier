<?php

namespace App\Console\Commands;

use App\Notifications\WeekDuty;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;

class NoticeWeekDutyCommand extends Command
{
    use Notifiable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notice-week-duty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send week duty notification by Slack';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->notify(new WeekDuty());
        $this->line('Send Completed.');
    }

    /**
     * Slackチャンネルに対する通知をルートする
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_INCOMING_WEBHOOK_URL');
    }
}
