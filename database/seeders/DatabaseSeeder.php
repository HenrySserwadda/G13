<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
 public function run(): void
{
    // Ensure roles don't already exist before creating
    if (!Role::where('name', 'systemadmin')->exists()) {
        Role::create(['name' => 'systemadmin']);
    }

    if (!Role::where('name', 'supplier')->exists()) {
        Role::create(['name' => 'supplier']);
    }

    if (!Role::where('name', 'staff')->exists()) {
        Role::create(['name' => 'staff']);
    }

    if (!Permission::where('name', 'manage raw materials')->exists()) {
        Permission::create(['name' => 'manage raw materials']);
    }

    // Call additional seeders
    $this->call([
        SupplierSeeder::class,
        OrdersandProductsSeeder::class,
        RawMaterialSeeder::class,
        SupplyCenterSeeder::class,
    ]);
}
}
// Additional users
//User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
//User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);
