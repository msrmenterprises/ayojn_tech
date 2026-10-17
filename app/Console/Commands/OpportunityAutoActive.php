<?php

namespace App\Console\Commands;

use App\Models\Collaborate;
use App\SystemNotification;
use Illuminate\Console\Command;
use DB;

class OpportunityAutoActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:active';

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
        DB::table('opportunities')->where('status','=',0)->update(array('status'=>1));
    }
}
