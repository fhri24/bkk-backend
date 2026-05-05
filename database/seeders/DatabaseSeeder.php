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
            // 1. Jalankan ini pertama agar Role & Permission tersedia di database
            RolePermissionSeeder::class,

            // 2. Baru jalankan seeder lainnya
            UserSeeder::class,
            NewsSeeder::class,
            EventSeeder::class,
        ]);
    }
}