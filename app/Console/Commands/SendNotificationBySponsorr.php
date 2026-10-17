<?php

namespace App\Console\Commands;

use App\SystemNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNotificationBySponsorr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $day = Carbon::now()->dayOfWeekIso;
        $notifications = SystemNotification::where('is_cron', 1)->where('cron_day', $day)->where('is_executed', 0)
            ->get();
        if ($notifications->first()) {
            foreach ($notifications as $notification) {
                $path = 'mail/notification';
                //$send_mail = send_mail($smsdata);
                $data = ['to' => 'bhalani.akashb@gmail.com',
                    'from' => env('MAIL_USERNAME'),
                    'reply_to' => env('MAIL_USERNAME'),
                    'from_name' => '',
                    'message' => $notification->message,
                    'path' => $path];

                $data123 = Mail::send('/mail/notification', ['data' => $data], function ($m) use ($data) {
                    $m->to($data['to'])->subject("Notification from Ayojn")->getSwiftMessage()
                        ->getHeaders()
                        ->addTextHeader('x-mailgun-native-send', 'true');
                });
                $notification->is_executed = 1;
                $notification->save();
            }
        }
    }
}
