<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['description' => 'Super Administrator']);
        $adminBkkRole   = Role::firstOrCreate(['name' => 'admin_bkk'],   ['description' => 'Admin BKK']);
        $kepalaBkkRole  = Role::firstOrCreate(['name' => 'kepala_bkk'],  ['description' => 'Kepala BKK']);
        $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan'],  ['description' => 'Perusahaan']);

        if (!User::where('email', 'superadmin@bkk.com')->exists()) {
            DB::table('users')->insert([
                'name'       => 'Super Admin',
                'email'      => 'superadmin@bkk.com',
                'password'   => Hash::make('password123'),
                'role_id'    => $superAdminRole->id,
                'is_active'  => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!User::where('email', 'admin@bkk.com')->exists()) {
            DB::table('users')->insert([
                'name'       => 'Admin BKK',
                'email'      => 'admin@bkk.com',
                'password'   => Hash::make('password123'),
                'role_id'    => $adminBkkRole->id,
                'is_active'  => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!User::where('email', 'kepala@bkk.com')->exists()) {
            DB::table('users')->insert([
                'name'       => 'Kepala BKK',
                'email'      => 'kepala@bkk.com',
                'password'   => Hash::make('password123'),
                'role_id'    => $kepalaBkkRole->id,
                'is_active'  => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!User::where('email', 'company@majujaya.com')->exists()) {
            $companyUserId = DB::table('users')->insertGetId([
                'name'       => 'PT. Maju Jaya',
                'email'      => 'company@majujaya.com',
                'password'   => Hash::make('password123'),
                'role_id'    => $perusahaanRole->id,
                'is_active'  => DB::raw('true'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!Company::where('user_id', $companyUserId)->exists()) {
                DB::table('companies')->insert([
                    'user_id'        => $companyUserId,
                    'company_name'   => 'PT. Maju Jaya',
                    'industry'       => 'Technology',
                    'contact_person' => 'Budi Santoso',
                    'phone'          => '021-123456',
                    'address'        => 'Jakarta',
                    'website'        => 'https://majujaya.com',
                    'is_verified'    => DB::raw('true'),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        $this->command->info('Seeding selesai!');
    }
}