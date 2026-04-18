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
        // Panggil semua seeder yang kamu butuhkan di sini
        $this->call([
            UserSeeder::class,
            // Tambahkan seeder lain kalau ada, misalnya ProductSeeder::class, dll.
        ]);
    }
}