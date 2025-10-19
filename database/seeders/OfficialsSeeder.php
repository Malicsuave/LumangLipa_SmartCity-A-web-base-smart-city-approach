<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Official;

class OfficialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Official::truncate();

        // Create sample officials
        $officials = [
            [
                'position' => 'Captain',
                'name' => 'Juan Dela Cruz',
                'committee' => null,
                'profile_pic' => null
            ],
            [
                'position' => 'Councilor',
                'name' => 'Maria Santos',
                'committee' => 'Health Committee',
                'profile_pic' => null
            ],
            [
                'position' => 'Councilor',
                'name' => 'Pedro Rodriguez',
                'committee' => 'Peace and Order',
                'profile_pic' => null
            ],
            [
                'position' => 'Councilor',
                'name' => 'Ana Garcia',
                'committee' => 'Education Committee',
                'profile_pic' => null
            ],
            [
                'position' => 'SK Chairman',
                'name' => 'Miguel Torres',
                'committee' => null,
                'profile_pic' => null
            ],
            [
                'position' => 'Secretary',
                'name' => 'Rosa Mendoza',
                'committee' => null,
                'profile_pic' => null
            ],
            [
                'position' => 'Treasurer',
                'name' => 'Carlos Reyes',
                'committee' => null,
                'profile_pic' => null
            ]
        ];

        foreach ($officials as $official) {
            Official::create($official);
        }
    }
}
