<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangayOfficial;

class BarangayOfficialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangayOfficial::updateOrCreate(
            ['id' => 1],
            [
                'captain_name' => 'HON. NOVILITO M. MANALO',
                'secretary_name' => 'APRIL JANE J. SISCAR',
                'treasurer_name' => 'JOSEPHINE R. QUISTO',
                'sk_chairperson_name' => 'HON. JOHN MARCO C. ARRIOLA',
                'councilor1_name' => 'HON. ROLDAN A. ROSITA',
                'councilor2_name' => 'HON. LEXTER D. MAQUINTO',
                'councilor3_name' => 'HON. RICHARD C. CANOSA',
                'councilor4_name' => 'HON. RODOLFO U. MANALO JR',
                'councilor5_name' => 'HON. ROSENDO T. BABADILLA',
                'councilor6_name' => 'HON. JAIME C. LAQUI',
                'councilor7_name' => 'HON. RECHEL R. CIRUELAS',
            ]
        );
    }
}
