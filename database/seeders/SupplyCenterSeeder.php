<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplyCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     SupplyCenter::create([
        'name' => 'Main Distribution Center',
        'location' => 'Kampala',
        'capacity' => 1000
    ]);
    
    SupplyCenter::create([
        'name' => 'Northern Hub',
        'location' => 'Gulu',
        'capacity' => 300
    ]);
    SupplyCenter::create([
        'name' => 'Premium Center',
        'location' => 'Entebbe',
        'capacity' => 600
    ]);
    
    SupplyCenter::create([
        'name' => 'Horizon Distribution Center',
        'location' => 'Mbale',
        'capacity' => 400
    ])
    }
}
