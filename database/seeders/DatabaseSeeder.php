<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\ShippingSeeder;
use Database\Seeders\RoleNPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
  
        $this->call([
            RoleNPermissionSeeder::class,
            StatusSeeder::class,
            ShippingSeeder::class,
        ]);
    }
}
