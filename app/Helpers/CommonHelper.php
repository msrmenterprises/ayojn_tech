<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\NotificationSetting;
use App\SystemNotification;
use Illuminate\Support\Facades\Mail;
use DB;
use App\User;
class CommonHelper
{

    public static function getConfig($key = null)
    {
        $site_config = DB::table('configuration')->where('config_text',$key)->get();
        $config = [];
        foreach ($site_config as $config_item) {
            $config['ayojn_service'] = $config_item->config_amt;
            $config['config_type'] = $config_item->config_type;
            $config['user_earn'] = $config_item->user_earn;
        }
        // if ($key) {
        //     return isset($config[$key]) ? $config[$key] : null;
        // }
        return $config;

    }

    public static function calculateAyojanEarning($type, $amount, $percentage)
    {
        if ($type == 'percentage') {
            $earning = $amount * $percentage / 100;
            return $earning;
        }else if ($type == 'fixed') {
            return $percentage; // Assuming fixed amount is directly the percentage value
        } else {
            return 0; // Default case if type is not recognized
        } 

        return 0; 
    }
    
    

    public static function userGetAyojanReward($key,$userid,$transactionId,$wallet_type)
    {   
        $siteConfig = DB::table('configuration')->where('config_text',$key)->first();
        $amount = $siteConfig->config_amt;
        $userPer = $siteConfig->user_earn;
        //user_earn > 0
        if($userPer > 0)
        {
            
            $wallet_amt = $amount*$userPer/100;

            //check is user refer or not
            $userDetails = User::where('id',$userid)->first();
            if(!is_null($userDetails->refer_by)) 
            {
                $referUserId = $userDetails->refer_by;
                $walletupdateReferUser = User::where('id', $referUserId)->update(array('wallet_marketplace'=>DB::raw("wallet_marketplace + $wallet_amt")));
                $insarray = array('user_id' => $referUserId,'points_by'=>12,'trans_id'=>0,'point'=>$wallet_amt,'wallet_type'=>1,'point_type'=>'CR','created_at'=>now());
                $walletDB = DB::table('wallet_logs')->insert($insarray);
            }
                $walletupdateUser = User::where('id', $userid)->update(array('wallet_marketplace'=>DB::raw("wallet_marketplace + $wallet_amt")));
                $insarray = array('user_id' => Auth::user()->id,'points_by'=>$wallet_type,'trans_id'=>$transactionId,'point'=>$wallet_amt,'point_type'=>'CR','wallet_type'=>1,'created_at'=>now());
                $walletDB = DB::table('wallet_logs')->insert($insarray);
        }
       

    }

    public static function sendNotification($userDetails, $message, $link)
    {
        $notificationSetting = NotificationSetting::where('user_id', $userDetails->id)->first();
        //dd($notificationSetting);

        if (! empty($notificationSetting) && ($notificationSetting->inapp == 1 || $notificationSetting->email == 1)) {
            $notification = new SystemNotification();
            $notification->user_id = $userDetails->id;
            $notification->message = $message;
            $notification->link = $link;
            if ($notificationSetting->inapp == 1) {
                $notification->in_app = 1;
            }
            if ($notificationSetting->email == 1) {
                $notification->email = 1;
                if (! empty($notificationSetting->days)) {
                    $notification->is_cron = 1;
                    $notification->cron_day = $notificationSetting->days;
                    $notification->is_executed = 0;
                } else {
                    $path = 'mail/notification';
                    //$send_mail = send_mail($smsdata);
                    $data = ['to' => 'bhalani.akashb@gmail.com',
                        'from' => env('MAIL_USERNAME'),
                        'reply_to' => env('MAIL_USERNAME'),
                        'from_name' => '',
                        'message' => $message,
                        'link' => $link,
                        'path' => $path];

                    $data123 = Mail::send('/mail/notification', ['data' => $data], function ($m) use ($data) {
                        $m->to($data['to'])->subject("Notification from Ayojn")->getSwiftMessage()
                            ->getHeaders()
                            ->addTextHeader('x-mailgun-native-send', 'true');
                    });
                }
            }
            $notification->save();
        }
    }
}
