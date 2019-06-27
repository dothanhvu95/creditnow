<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\AcounttantNote;
use App\HistoryLog_View;
use App\Support;
use App\CicLog;
use App\User;
use Excel;
use DB;
use Illuminate\Support\Facades\Log;
use File;

class MailCommand extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test cron job';

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
    public function handle(Request $request)
    {
        $update = DB::table('test')->insert(['name'=>'ahoho']);
        Log::debug('ahihi');
       
        \Log::info('Deleted all img temp folder '. \Carbon\Carbon::now());
       
        
                
        Storage::put('file.xlsx',$result);



               
    }
}
// Username: creditnow
// Password: aLRwaTX9uorp4TJhIX