<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeWeekDutyCommand extends Command
{
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
        $duty = '@hideyoshi';
        echo "今週の当番は {$duty} です\n";
    }
}
