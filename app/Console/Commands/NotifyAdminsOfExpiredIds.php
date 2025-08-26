<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resident;
use App\Models\SeniorCitizen;
use App\Models\User;
use App\Notifications\IdExpiredNotification;

class NotifyAdminsOfExpiredIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expired-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find expired Resident IDs
        $expiredResidents = Resident::where('id_status', 'issued')
            ->whereNotNull('id_expires_at')
            ->where('id_expires_at', '<', now())
            ->get();

        // Find expired Senior Citizen IDs
        $expiredSeniors = SeniorCitizen::where('senior_id_status', 'issued')
            ->whereNotNull('senior_id_expires_at')
            ->where('senior_id_expires_at', '<', now())
            ->get();

        // Get all admin users
        $admins = User::whereIn('role_id', [1, 2])->get();

        foreach ($admins as $admin) {
            foreach ($expiredResidents as $resident) {
                $admin->notify(new IdExpiredNotification($resident));
            }
            foreach ($expiredSeniors as $senior) {
                $admin->notify(new IdExpiredNotification($senior));
            }
        }

        $this->info('Admin(s) notified of expired IDs.');
    }
}
