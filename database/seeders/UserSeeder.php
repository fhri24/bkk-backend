<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@bkk.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
            ]
        );

        // 2. Create Admin BKK
        $adminBkkRole = Role::where('name', 'admin_bkk')->first();
        $adminBkk = User::firstOrCreate(
            ['email' => 'admin@bkk.com'],
            [
                'name' => 'Admin BKK',
                'password' => Hash::make('password123'),
                'role_id' => $adminBkkRole->id,
                'is_active' => true,
            ]
        );

        // 3. Create Kepala BKK
        $kepalaBkkRole = Role::where('name', 'kepala_bkk')->first();
        $kepalaBkk = User::firstOrCreate(
            ['email' => 'kepala@bkk.com'],
            [
                'name' => 'Kepala BKK',
                'password' => Hash::make('password123'),
                'role_id' => $kepalaBkkRole->id,
                'is_active' => true,
            ]
        );

        // 4. Create Sample Company with Company Account
        $perusahaanRole = Role::where('name', 'perusahaan')->first();
        $companyUser = User::firstOrCreate(
            ['email' => 'company@majujaya.com'],
            [
                'name' => 'PT. Maju Jaya',
                'password' => Hash::make('password123'),
                'role_id' => $perusahaanRole->id,
                'is_active' => true,
            ]
        );

        // Create company profile linked to user
        Company::firstOrCreate(
            ['user_id' => $companyUser->id],
            [
                'company_name' => 'PT. Maju Jaya',
                'industry' => 'Technology',
                'contact_person' => 'Budi Santoso',
                'phone' => '021-123456',
                'address' => 'Jakarta',
                'website' => 'https://majujaya.com',
                'is_verified' => true,
            ]
        );

        // Note: Students should register themselves through the registration page

        $this->command->info('Users seeded successfully!');
        $this->command->info('Super Admin: superadmin@bkk.com (password: password123)');
        $this->command->info('Admin BKK: admin@bkk.com (password: password123)');
        $this->command->info('Kepala BKK: kepala@bkk.com (password: password123)');
        $this->command->info('Company: company@majujaya.com (password: password123)');
        $this->command->info('');
        $this->command->info('Students can register at: http://localhost:8000/register');
    }
}
