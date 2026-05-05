<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Publik;
use App\Models\User;
use App\Models\Role;

class PublikAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles dengan updateOrCreate
        Role::updateOrCreate(['name' => 'admin_bkk'], ['display_name' => 'Admin BKK']);
        Role::updateOrCreate(['name' => 'publik'], ['display_name' => 'Publik']);
    }

    public function test_admin_can_view_publik_list()
    {
        // Ambil admin role
        $role = Role::where('name', 'admin_bkk')->first();
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        // Buat publik
        $publik = Publik::create([
            'nisn' => '1234567890',
            'nama_lengkap' => 'John Public',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'tahun_lulus' => 2018,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test',
        ]);

        User::create([
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'publik')->first()->id,
            'userable_id' => $publik->id,
            'userable_type' => Publik::class,
        ]);

        // Test akses sebagai admin (bypass middleware)
        $response = $this->actingAs($admin)
            ->withoutMiddleware()
            ->get(route('admin.publik.index'));

        $response->assertStatus(200);
        $response->assertSee('John Public');
    }

    public function test_admin_can_delete_publik()
    {
        // Ambil admin role
        $role = Role::where('name', 'admin_bkk')->first();
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        // Buat publik
        $publik = Publik::create([
            'nisn' => '1234567890',
            'nama_lengkap' => 'John Public',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'tahun_lulus' => 2018,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test',
        ]);

        $publik_role = Role::where('name', 'publik')->first();
        $user = User::create([
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => bcrypt('password'),
            'role_id' => $publik_role->id,
            'userable_id' => $publik->id,
            'userable_type' => Publik::class,
        ]);

        // Verify initial state
        $this->assertDatabaseHas('publik', ['id' => $publik->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        // Test delete (bypass middleware)
        $response = $this->actingAs($admin)
            ->withoutMiddleware()
            ->delete(route('admin.publik.destroy', $publik));

        $response->assertRedirect(route('admin.publik.index'));

        // Verify publik dan user terhapus
        $this->assertDatabaseMissing('publik', ['id' => $publik->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
