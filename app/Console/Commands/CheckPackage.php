<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use DateTime;

class CheckPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PackageChecker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'subscription status checker and updater.';

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
     * @return int
     */
    public function handle()
    {
        \Log::info("subscription checker is working perfectly!");

        $all_packages = DB::table('packages')->where('subscription_status', 'Active')->get();

        foreach($all_packages as $package)
        {
          $date1 = time();
          $date2 = strtotime($package->subscription_expire_date);
          $date_diff = $date2 - $date1;
          $date_diff = $date_diff / (60 * 60 * 24);
          if($date_diff > 1)
          {
            $diff = round($date_diff);
          }
          elseif($date_diff < 1 && $date_diff > 0)
          {
            $diff = 1;
          }
          elseif($date_diff == 0)
          {
            $diff = 0;
          }
          else
          {
            $diff = 0;
          }

          if($diff == 0)
          {
            $update = DB::table('packages')
                ->where('id', $package->id)
                ->update([
                    'subscription_status' => 'Expired'
                ]);
          }
          elseif($diff > 0)
          {
            $update = DB::table('packages')
                ->where('id', $package->id)
                ->update([
                    'subscription_status' => 'Active'
                ]);
          }

        }

        return 0;
    }
}
