<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SupplierUserSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'user_id' => Str::uuid()->toString(),
                'name' => 'Supplier One',
                'email' => 'supplier1@example.com',
                'category' => 'supplier',
                'status' => 'approved',
                'is_admin' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => Str::uuid()->toString(),
                'name' => 'Supplier Two',
                'email' => 'supplier2@example.com',
                'category' => 'supplier',
                'status' => 'pending',
                'is_admin' => 0,
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => Str::uuid()->toString(),
                'name' => 'Supplier Three',
                'email' => 'supplier3@example.com',
                'category' => 'supplier',
                'status' => 'rejected',
                'is_admin' => 0,
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($suppliers);
    }
}
