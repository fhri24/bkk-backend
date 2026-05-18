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

        // 1. Create Super Admin
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@bkk.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'is_active' => DB::raw("'true'::boolean"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Create Admin BKK
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@bkk.com'],
            [
                'name' => 'Admin BKK',
                'password' => Hash::make('password123'),
                'role_id' => $adminBkkRole->id,
                'is_active' => DB::raw("'true'::boolean"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Create Kepala BKK
        DB::table('users')->updateOrInsert(
            ['email' => 'kepala@bkk.com'],
            [
                'name' => 'Kepala BKK',
                'password' => Hash::make('password123'),
                'role_id' => $kepalaBkkRole->id,
                'is_active' => DB::raw("'true'::boolean"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 4. Create Sample Company with Company Account
        DB::table('users')->updateOrInsert(
            ['email' => 'company@majujaya.com'],
            [
                'name' => 'PT. Maju Jaya',
                'password' => Hash::make('password123'),
                'role_id' => $perusahaanRole->id,
                'is_active' => DB::raw("'true'::boolean"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Get company user ID
        $companyUser = User::where('email', 'company@majujaya.com')->first();

        // Create company profile linked to user
        DB::table('companies')->updateOrInsert(
            ['user_id' => $companyUser->id],
            [
                'company_name' => 'PT. Maju Jaya',
                'industry' => 'Technology',
                'contact_person' => 'Budi Santoso',
                'phone' => '021-123456',
                'address' => 'Jakarta',
                'website' => 'https://majujaya.com',
                'is_verified' => DB::raw("'true'::boolean"),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
=======
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
>>>>>>> be78593ee1f26a5efb19b9484dd2a64eeac87688

        $this->command->info('Seeding selesai!');
    }
}
