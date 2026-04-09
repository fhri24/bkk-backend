<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Workshop Skills Interview dan CV Writing',
                'slug' => 'workshop-skills-interview',
                'description' => 'Workshop mendalam tentang teknik interview dan pembuatan CV yang menarik dipandu oleh praktisi HR berpengalaman.',
                'location' => 'Aula SMKN 1 Garut',
                'start_date' => now()->addDays(7)->setTime(10, 0),
                'end_date' => now()->addDays(7)->setTime(12, 30),
                'capacity' => 50,
                'organizer' => 'BKK SMKN 1 Garut',
                'category' => 'Workshop',
                'image' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Program Magang PT Maju Jaya 2026',
                'slug' => 'program-magang-pt-maju-jaya',
                'description' => 'Penjelasan program magang dan kesempatan bergabung dengan PT Maju Jaya, salah satu mitra industri terpercaya kami.',
                'location' => 'Ruang Multimedia SMKN 1 Garut',
                'start_date' => now()->addDays(14)->setTime(13, 0),
                'end_date' => now()->addDays(14)->setTime(15, 0),
                'capacity' => 100,
                'organizer' => 'PT Maju Jaya',
                'category' => 'Sosialisasi Perusahaan',
                'image' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Career Fair 2026 - Industri & Pekerjaan',
                'slug' => 'career-fair-2026',
                'description' => 'Pameran karir bersama 20+ perusahaan untuk mencari talenta terbaik dan berbagi informasi peluang kerja.',
                'location' => 'Lapangan Sekolah SMKN 1 Garut',
                'start_date' => now()->addDays(30)->setTime(8, 0),
                'end_date' => now()->addDays(30)->setTime(16, 0),
                'capacity' => 500,
                'organizer' => 'BKK SMKN 1 Garut bersama Mitra Industri',
                'category' => 'Career Fair',
                'image' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Sertifikasi Kompetensi Gratis - Gelombang 1',
                'slug' => 'sertifikasi-kompetensi-gratis-gelombang-1',
                'description' => 'Program sertifikasi kompetensi gratis untuk meningkatkan daya saing lulusan di dunia industri.',
                'location' => 'Laboratorium Komputer SMKN 1 Garut',
                'start_date' => now()->addDays(45)->setTime(9, 0),
                'end_date' => now()->addDays(45)->setTime(17, 0),
                'capacity' => 40,
                'organizer' => 'Lembaga Sertifikasi Kompetensi',
                'category' => 'Pelatihan',
                'image' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Seminar: Soft Skills untuk Kesuksesan Karir',
                'slug' => 'seminar-soft-skills',
                'description' => 'Seminar tentang pentingnya soft skills seperti komunikasi, leadership, dan teamwork dalam dunia kerja modern.',
                'location' => 'Aula Besar SMKN 1 Garut',
                'start_date' => now()->addDays(21)->setTime(14, 0),
                'end_date' => now()->addDays(21)->setTime(16, 0),
                'capacity' => 150,
                'organizer' => 'BKK SMKN 1 Garut',
                'category' => 'Seminar',
                'image' => null,
                'is_published' => true,
            ],
            [
                'title' => 'Alumni Berbagi Pengalaman: Success Stories',
                'slug' => 'alumni-berbagi-pengalaman',
                'description' => 'Sharing session dari alumni yang telah sukses bekerja di berbagai perusahaan besar dan startup.',
                'location' => 'Ruang Kelas 12',
                'start_date' => now()->addDays(35)->setTime(15, 0),
                'end_date' => now()->addDays(35)->setTime(17, 0),
                'capacity' => 60,
                'organizer' => 'BKK SMKN 1 Garut',
                'category' => 'Mentoring',
                'image' => null,
                'is_published' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(
                ['slug' => $event['slug']],
                $event
            );
        }
    }
}
