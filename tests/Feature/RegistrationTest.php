<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_alumni_can_register()
    {
        $response = $this->postJson('/api/register', [
            'role'          => 'alumni',
            'name'          => 'John Doe',
            'email'         => 'john@example.com',
            'password'      => 'password123',
            'password_confirmation' => 'password123',
            'nisn'          => '1234567890',
            'nama_lengkap'  => 'John Doe',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Jakarta',
            'tanggal_lahir' => '2005-01-01',
            'tahun_lulus'   => 2023,
            'no_hp'         => '081234567890',
            'alamat'        => 'Jl. Test No. 123',
            'jurusan'       => 'Teknik Informatika',
        ]);

        $response->assertStatus(200);
    }

    public function test_publik_can_register()
    {
        $response = $this->postJson('/api/register', [
            'role'          => 'publik',
            'name'          => 'Jane Doe',
            'email'         => 'jane@example.com',
            'password'      => 'password123',
            'password_confirmation' => 'password123',
            'nisn'          => '0987654321',
            'nama_lengkap'  => 'Jane Doe',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Bandung',
            'tanggal_lahir' => '2000-05-15',
            'tahun_lulus'   => 2018,
            'no_hp'         => '082345678901',
            'alamat'        => 'Jl. Test 2 No. 456',
        ]);

        $response->assertStatus(200);
    }
}
