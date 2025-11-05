<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'announcements:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate announcements that have passed their event date and time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired announcements...');
        
        $now = Carbon::now();
        $deactivatedCount = 0;

        // Get all active announcements with a date
        $announcements = Announcement::where('is_active', true)
            ->whereNotNull('date')
            ->get();

        foreach ($announcements as $announcement) {
            $shouldDeactivate = false;
            
            // Check if the announcement has passed its date/time
            if ($announcement->date && $announcement->end_time) {
                // If both date and end_time exist, combine them
                $endDateTime = Carbon::parse($announcement->date->format('Y-m-d') . ' ' . $announcement->end_time);
                
                if ($now->greaterThan($endDateTime)) {
                    $shouldDeactivate = true;
                    $this->warn("  ✗ {$announcement->title}: Expired (ended at {$endDateTime->format('Y-m-d h:i A')})");
                }
            } elseif ($announcement->date) {
                // If only date exists, check if the date has passed (end of day)
                $endOfDay = Carbon::parse($announcement->date)->endOfDay();
                
                if ($now->greaterThan($endOfDay)) {
                    $shouldDeactivate = true;
                    $this->line("  - {$announcement->title}: Expired (date passed: {$announcement->date->format('Y-m-d')})");
                }
            }

            // Deactivate if expired
            if ($shouldDeactivate) {
                $announcement->is_active = false;
                $announcement->save();
                $deactivatedCount++;
                
                Log::info("Announcement deactivated: {$announcement->title} (ID: {$announcement->id})");
            }
        }

        if ($deactivatedCount > 0) {
            $this->info("✓ Deactivated {$deactivatedCount} expired announcement(s).");
        } else {
            $this->info('✓ No expired announcements found.');
        }

        return 0;
    }
}
