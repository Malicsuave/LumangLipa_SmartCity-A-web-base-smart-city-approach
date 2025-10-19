<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Official;
use App\Models\BarangayOfficial;

class MigrateBarangayOfficialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing officials data
        Official::truncate();

        // Get the barangay officials data
        $barangayOfficial = BarangayOfficial::first();
        
        if (!$barangayOfficial) {
            $this->command->info('No barangay officials data found to migrate.');
            return;
        }

        $officials = [];

        // Captain
        if ($barangayOfficial->captain_name) {
            $officials[] = [
                'position' => 'Captain',
                'name' => $barangayOfficial->captain_name,
                'committee' => null,
                'profile_pic' => $this->extractFilename($barangayOfficial->captain_photo)
            ];
        }

        // Secretary
        if ($barangayOfficial->secretary_name) {
            $officials[] = [
                'position' => 'Secretary',
                'name' => $barangayOfficial->secretary_name,
                'committee' => null,
                'profile_pic' => $this->extractFilename($barangayOfficial->secretary_photo)
            ];
        }

        // Treasurer
        if ($barangayOfficial->treasurer_name) {
            $officials[] = [
                'position' => 'Treasurer',
                'name' => $barangayOfficial->treasurer_name,
                'committee' => null,
                'profile_pic' => $this->extractFilename($barangayOfficial->treasurer_photo)
            ];
        }

        // SK Chairperson
        if ($barangayOfficial->sk_chairperson_name) {
            $officials[] = [
                'position' => 'SK Chairman',
                'name' => $barangayOfficial->sk_chairperson_name,
                'committee' => null,
                'profile_pic' => $this->extractFilename($barangayOfficial->sk_chairperson_photo)
            ];
        }

        // Councilors
        for ($i = 1; $i <= 7; $i++) {
            $nameField = "councilor{$i}_name";
            $photoField = "councilor{$i}_photo";
            $committeeField = "councilor{$i}_committee";
            
            if ($barangayOfficial->$nameField) {
                $officials[] = [
                    'position' => 'Councilor',
                    'name' => $barangayOfficial->$nameField,
                    'committee' => $barangayOfficial->$committeeField,
                    'profile_pic' => $this->extractFilename($barangayOfficial->$photoField)
                ];
            }
        }

        // Insert all officials
        foreach ($officials as $official) {
            Official::create($official);
            $this->command->info("Migrated: {$official['position']} - {$official['name']}");
        }

        $this->command->info('Migration completed successfully!');
    }

    /**
     * Extract filename from the photo path
     */
    private function extractFilename($photoPath)
    {
        if (!$photoPath) {
            return null;
        }
        
        // If path is like "officials/filename.ext", extract just the filename
        if (strpos($photoPath, 'officials/') === 0) {
            return substr($photoPath, 10); // Remove "officials/" prefix
        }
        
        return $photoPath;
    }
}
