<?php

namespace App\Console\Commands;

use App\GoogleSheet;
use App\Notifications\WeekDuty;
use Carbon\Carbon;
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
    protected $description = 'send next week duty notification by Slack';

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
     * @throws \Exception
     */
    public function handle()
    {
        $sheets = GoogleSheet::instance();
        $sheet_id = env('GOOGLE_SPREAD_SHEET_ID');

        Carbon::setWeekStartsAt(Carbon::MONDAY); // 月曜始まりになるよう明記
        $columnLabels = GoogleSheet::columnLabels();

        // 前方にあるデータ以外の列の数
        $infoColumnNum = 3;
        $dutyIdColumn = 'B'; // SlackメンバーIDが格納されているカラム
        $dutyIdRowStart = 3;
        $dutyIdRowEnd = 12;

        $weekData = $sheets->spreadsheets_values->get($sheet_id, 'D2:CN2');
        $startDatesOfWeek = $weekData->values[0];

        sleep(1);

        $today = Carbon::now();

        $dutyIds = [];

        // 当番表から該当する週の情報を取得する
        foreach($startDatesOfWeek as $index => $startDateOfWeek) {
            $startDate = new Carbon($startDateOfWeek);

            if ($today < $startDate) {
                // その週の当番になっている人を特定
                for ($rowIndex = $dutyIdRowStart; $rowIndex < $dutyIdRowEnd; $rowIndex++) {
                    $dutyData = $sheets->spreadsheets_values
                        ->get($sheet_id,$columnLabels[$index + $infoColumnNum] . $rowIndex);
                    $duty = $dutyData->values[0];

                    sleep(1);

                    // 登板になっている人のSlackメンバーIDを取得し配列に突っ込む
                    if ($duty != null) {
                        $dutyIdData = $sheets->spreadsheets_values
                            ->get($sheet_id,$dutyIdColumn . $rowIndex);
                        $dutyIds[] = $dutyIdData->values[0][0];
                    }
                    sleep(1);
                }
                var_dump($dutyIds);
                break;
            }
        }

        $this->notify(new WeekDuty($dutyIds));
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
