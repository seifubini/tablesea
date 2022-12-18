<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\Table_To_Reservation;
use Carbon\Carbon;

class CheckReservationStatusEveryTwoDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel reservation if not confirmed in 48 hours.';

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
        $reservations = DB::table('reservations')
            ->where('reservation_status', '=', 'booked')
            ->where('created_at', '>',Carbon::parse('-48 hours'))
            ->get();

        foreach ($reservations as $reservation)
        {
            if($reservation->reservation_status != "confirmed")
            {
                $reservation_id = $reservation->id;

                DB::table('reservations')
                    ->where('id', $reservation_id)
                    ->update([ 'reservation_status' => 'cancelled']);

            }
        }

        $this->info('Task Update Reservation Status to Cancelled after 48 Hours completed successfully.');
        return 0;
    }
}
