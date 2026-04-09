<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Akun Student buat Ngetes
        User::create([
            'name' => 'Faisal Student',
            'email' => 'faisal@gmail.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // Sesuaikan dengan ID Role Student di tabel roles kamu
            'is_active' => true,
        ]);
    }
}