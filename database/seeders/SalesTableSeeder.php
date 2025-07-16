<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use Carbon\Carbon;

class SalesTableSeeder extends Seeder
{
    public function run(): void
     {
        for ($i = 1; $i <= 10; $i++) {
            Sale::create([
                'supply_center_id' => rand(1, 3),
                'amount' => rand(100000, 1000000),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
