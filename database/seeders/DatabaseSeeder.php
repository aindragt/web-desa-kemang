<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \database\seeders\UserSeeder::class,
            \database\seeders\StatistikSeeder::class,
            \database\seeders\BeritaSeeder::class,
            \database\seeders\PengaturanSeeder::class,
        ]);
    }
}
