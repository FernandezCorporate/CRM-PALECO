<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // The main execution point for all application seeders.
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // Future seeders (like DepartmentSeeder or TeamSeeder) will go here.
        ]);
    }
}