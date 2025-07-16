<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplyCenter;

class SupplyCenterSeeder extends Seeder
{
    public function run()
    {
        $centers = [
            ['name' => 'Durabag Manufacturers Main Branch', 'location' => 'Kampala'],
            ['name' => 'Durabag Arua', 'location' => 'Arua'],
            ['name' => 'Durabag Mbale', 'location' => 'Mbale'],
            ['name' => 'Durabag Mbarara', 'location' => 'Mbarara'],
        ];

        foreach ($centers as $center) {
            SupplyCenter::create($center);
        }
    }
}
