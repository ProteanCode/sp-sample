<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('testing')) {
            $this->runTestSeeders();
        }

        $this->call(UserSeeder::class);
    }

    private function runTestSeeders(): void
    {
        $this->call(ImageAuthorsSeeder::class);
    }
}
