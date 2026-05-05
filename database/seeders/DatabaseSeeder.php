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
            RoleSeeder::class,
            // RolePermissionSeeder::class, // hapus dulu sampai dikonversi
            UserSeeder::class,
            NewsSeeder::class,
            EventSeeder::class,
        ]);
    }
}