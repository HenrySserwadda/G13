<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;


class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 5 suppliers
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Supplier ' . $i,
                'email' => 'supplier' . $i . '@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // or Hash::make()
                'category' => 'supplier',
                'pending_category' => null,
                'status' => 'application approved',
                'user_id' => User::generateUserId('supplier'),
                'remember_token' => Str::random(10),
            ]);
        }
    }    
}
