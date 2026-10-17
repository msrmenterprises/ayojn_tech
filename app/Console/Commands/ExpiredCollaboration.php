<?php

namespace App\Console\Commands;

use App\Models\Collaborate;
use App\SystemNotification;
use Illuminate\Console\Command;

class ExpiredCollaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:collaboration';

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
        $colleborations = Collaborate::whereDay('created_at', '>=', now()->day)->get();
        if (!empty($colleborations)) {
            foreach ($colleborations as $coll) {
                $coll->status = 3;
                $coll->save();
            }
        }
    }
}
