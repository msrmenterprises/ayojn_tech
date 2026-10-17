<?php

namespace App\Console\Commands;

use App\Models\Collaborate;
use App\SystemNotification;
use Illuminate\Console\Command;
use DB;

class UserAutoUpgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade:user';

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
        $type = DB::table('user_type')->get();
        foreach ($type as $key => $value) {
            if ($value->user_type == 1) {
                $user = DB::table('users')->where('user_type', 1)->where('is_kyc', 1)->where('wallet_balance', '>', $value->level)->get();
                foreach ($user as $u) {
                    if ($u->wallet_balance > $value->level) {
                        DB::table('users')->where('id', $u->id)->update(['user_type' => 2]);
                        SystemNotification::create([
                            'user_id' => $u->id,
                            'title' => 'Congratulations',
                            'message' => 'Your account has been upgraded to Premium User.',
                            'type' => 'upgrade',
                        ]);
                    }else{
                         DB::table('users')->where('id', $u->id)->update(['user_type' => 1]);
                    }
                    
                }
            }

            if ($value->user_type == 2) {
                $user = DB::table('users')->where('user_type', 2)->where('is_kyc', 1)->where('wallet_balance', '>', $value->level)->get();
                foreach ($user as $u) {
                    if ($u->wallet_balance > $value->level) {
                         DB::table('users')->where('id', $u->id)->update(['user_type' => 3]);
                        SystemNotification::create([
                            'user_id' => $u->id,
                            'title' => 'Congratulations',
                            'message' => 'Your account has been upgraded to Premium User.',
                            'type' => 'upgrade',
                        ]);
                    }else{
                         DB::table('users')->where('id', $u->id)->update(['user_type' => 2]);
                    }
                   
                }
            }


            if ($value->user_type == 3) {
                $user = DB::table('users')->where('user_type', 3)->where('is_kyc', 1)->where('wallet_balance', '>', $value->level)->get();
                foreach ($user as $u) {
                    if ($u->wallet_balance > $value->level) {
                        DB::table('users')->where('id', $u->id)->update(['user_type' => 4]);
                        SystemNotification::create([
                            'user_id' => $u->id,
                            'title' => 'Congratulations',
                            'message' => 'Your account has been upgraded to Premium User.',
                            'type' => 'upgrade',
                        ]);
                    }else{
                        DB::table('users')->where('id', $u->id)->update(['user_type' => 3]);
                    }
                    
                }
            }
        }

       
    }
}
