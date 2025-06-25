<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RawMaterialSeeder extends Seeder
{
    public function run()
    {
        // Get all users who are suppliers
        $suppliers = User::where('category', 'supplier')->get();

        if ($suppliers->isEmpty()) {
            echo "⚠️ No suppliers found. Please seed suppliers first.\n";
            return;
        }

        $materialTypes = ['Natural', 'Synthetic', 'Metal'];
        $materialNames = [
            'Cotton Fabric', 'Nylon Thread', 'Leather Sheet', 'Canvas Roll',
            'Polyester Fiber', 'Zippers Pack', 'Metal Buckles', 'Plastic Straps',
            'Elastic Bands', 'D-Rings', 'Velcro Tape', 'Foam Padding'
        ];

        $usedNames = [];

        foreach ($suppliers as $supplier) {
            $materialCount = 0;

            foreach ($materialNames as $materialName) {
                if (in_array($materialName, $usedNames)) {
                    continue;
                }

                DB::table('raw_materials')->insert([
                    'name' => $materialName,
                    'type' => fake()->randomElement($materialTypes),
                    'quantity' => fake()->numberBetween(10, 300),
                    'unit_price' => fake()->randomFloat(2, 2, 100),
                    'user_id' => $supplier->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $usedNames[] = $materialName;
                $materialCount++;

                if ($materialCount >= 4) break;
            }
        }
    }
}
