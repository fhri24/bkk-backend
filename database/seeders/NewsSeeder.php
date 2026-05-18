<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $newsItems = [
            [
                'title'        => 'Program Magang Baru dari PT Maju Jaya',
                'slug'         => 'program-magang-baru-pt-maju-jaya',
                'category'     => 'Kesempatan',
                'excerpt'      => 'PT Maju Jaya membuka program magang untuk 10 peserta didik dengan berbagai posisi',
                'content'      => 'PT Maju Jaya membuka program magang untuk 10 peserta didik dengan berbagai posisi. Program ini memberikan kesempatan bagi siswa SMKN 1 Garut untuk mendapatkan pengalaman kerja di industri manufaktur. Magang berlangsung selama 3 bulan dengan kompensasi menarik.',
                'image'        => null,
                'author_id'    => 1,
                'published_at' => now()->subDays(5),
                'is_published' => DB::raw('true'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Pengumuman Hasil Seleksi Pekerjaan Batch 2',
                'slug'         => 'pengumuman-hasil-seleksi-pekerjaan-batch-2',
                'category'     => 'Pengumuman',
                'excerpt'      => 'Hasil seleksi pekerjaan batch 2 sudah tersedia di portal BKK',
                'content'      => 'Dengan senang hati kami umumkan bahwa proses seleksi pekerjaan batch 2 telah selesai. Hasilnya dapat dilihat melalui portal BKK dengan login menggunakan akun siswa Anda.',
                'image'        => null,
                'author_id'    => 1,
                'published_at' => now()->subDays(10),
                'is_published' => DB::raw('true'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Workshop Skills Interview dan CV Writing',
                'slug'         => 'workshop-skills-interview-cv-writing',
                'category'     => 'Workshop',
                'excerpt'      => 'Bergabunglah dengan workshop gratis kami untuk meningkatkan skill interview dan pembuatan CV',
                'content'      => 'BKK mengadakan workshop gratis tentang skill interview dan pembuatan CV yang efektif. Workshop ini akan dipandu oleh praktisi HR berpengalaman dari berbagai perusahaan.',
                'image'        => null,
                'author_id'    => 1,
                'published_at' => now()->subDays(15),
                'is_published' => DB::raw('true'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Update Lowongan Pekerjaan Terbaru April 2026',
                'slug'         => 'update-lowongan-pekerjaan-terbaru-april-2026',
                'category'     => 'Lowongan',
                'excerpt'      => 'Tambahan 25 lowongan pekerjaan dari berbagai industri telah ditambahkan ke portal',
                'content'      => 'Bulan April ini kami telah menambahkan 25 lowongan pekerjaan dari berbagai industri termasuk manufaktur, IT, hospitality, dan jasa.',
                'image'        => null,
                'author_id'    => 1,
                'published_at' => now()->subDays(20),
                'is_published' => DB::raw('true'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Tips Sukses Melamar Pekerjaan di Era Digital',
                'slug'         => 'tips-sukses-melamar-pekerjaan-di-era-digital',
                'category'     => 'Tips Karir',
                'excerpt'      => 'Panduan lengkap tentang cara melamar pekerjaan secara online dengan efektif',
                'content'      => 'Proses pelamaran pekerjaan di era digital memiliki beberapa perbedaan dengan cara tradisional. Siapkan CV profesional, pahami deskripsi pekerjaan, dan manfaatkan media sosial profesional.',
                'image'        => null,
                'author_id'    => 1,
                'published_at' => now()->subDays(25),
                'is_published' => DB::raw('true'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($newsItems as $item) {
            $exists = DB::table('news')->where('slug', $item['slug'])->exists();
            if (!$exists) {
                DB::table('news')->insert($item);
            }
        }
    }
} 