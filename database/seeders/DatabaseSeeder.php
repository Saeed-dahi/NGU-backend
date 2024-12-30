<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Inventory\UnitSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(ClosingAccountSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AccountSeeder::class);
        // $this->call(StoreSeeder::class);
        // $this->call(CategorySeeder::class);
        $this->call(UnitSeeder::class);
    }
}
