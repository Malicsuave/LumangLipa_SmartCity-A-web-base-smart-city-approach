<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateResidentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'residents:update-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update residents with missing purok and occupation data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $residents = \App\Models\Resident::all();
        
        foreach($residents as $resident) {
            $resident->update([
                'purok' => $resident->purok ?: 'Purok ' . rand(1,7), 
                'occupation' => $resident->occupation ?: ($resident->profession_occupation ?: 'General Labor')
            ]);
        }
        
        $this->info("Updated " . count($residents) . " residents with purok and occupation data");
        return 0;
    }
}
