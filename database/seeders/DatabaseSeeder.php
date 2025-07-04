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
        // User::factory(10)->create();

        
        Role::create(['name' => 'systemadmin']);
        Role::create(['name' => 'supplier']);
        Role::create(['name' => 'staff']);
        Permission::create(['name' => 'manage raw materials']);


        $this->call([
            OrdersandProductsSeeder::class
        ]);
    }
}
// Additional users
//User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
//User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);
