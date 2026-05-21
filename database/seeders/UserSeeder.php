<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['description' => 'Super Administrator']);
        $adminBkkRole = Role::firstOrCreate(['name' => 'admin_bkk'], ['description' => 'Admin BKK']);
        $kepalaBkkRole = Role::firstOrCreate(['name' => 'kepala_bkk'], ['description' => 'Kepala BKK']);
        $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan'], ['description' => 'Perusahaan']);

        // Pakai updateOrInsert dengan is_active = DB::raw('true') untuk users (kolom boolean)
        // dan is_verified = 1 untuk companies (kolom integer)

        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@bkk.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'is_active' => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@bkk.com'],
            [
                'name' => 'Admin BKK',
                'password' => Hash::make('password123'),
                'role_id' => $adminBkkRole->id,
                'is_active' => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'kepala@bkk.com'],
            [
                'name' => 'Kepala BKK',
                'password' => Hash::make('password123'),
                'role_id' => $kepalaBkkRole->id,
                'is_active' => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'company@majujaya.com'],
            [
                'name' => 'PT. Maju Jaya',
                'password' => Hash::make('password123'),
                'role_id' => $perusahaanRole->id,
                'is_active' => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $companyUser = User::where('email', 'company@majujaya.com')->first();

        // FIX: is_verified = 1 (integer), bukan boolean
        DB::table('companies')->updateOrInsert(
            ['user_id' => $companyUser->id],
            [
                'company_name' => 'PT. Maju Jaya',
                'industry' => 'Technology',
                'contact_person' => 'Budi Santoso',
                'phone' => '021-123456',
                'address' => 'Jakarta',
                'website' => 'https://majujaya.com',
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Seeding selesai!');
        $this->command->info('superadmin@bkk.com / password123');
        $this->command->info('admin@bkk.com / password123');
        $this->command->info('kepala@bkk.com / password123');
        $this->command->info('company@majujaya.com / password123');
    }
}
