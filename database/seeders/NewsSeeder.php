<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsItems = [
            [
                'title' => 'Program Magang Baru dari PT Maju Jaya',
                'slug' => 'program-magang-baru-pt-maju-jaya',
                'category' => 'Kesempatan',
                'excerpt' => 'PT Maju Jaya membuka program magang untuk 10 peserta didik dengan berbagai posisi',
                'content' => 'PT Maju Jaya membuka program magang untuk 10 peserta didik dengan berbagai posisi. Program ini memberikan kesempatan bagi siswa SMKN 1 Garut untuk mendapatkan pengalaman kerja di industri manufaktur. Magang berlangsung selama 3 bulan dengan kompensasi menarik. Pendaftar harus memiliki IPK minimal 3.0 dan tidak sedang ada tanggungan pekerjaan lain.',
                'image' => null,
                'author_id' => 1,
                'published_at' => now()->subDays(5),
                'is_published' => true,
            ],
            [
                'title' => 'Pengumuman Hasil Seleksi Pekerjaan Batch 2',
                'slug' => 'pengumuman-hasil-seleksi-pekerjaan-batch-2',
                'category' => 'Pengumuman',
                'excerpt' => 'Hasil seleksi pekerjaan batch 2 sudah tersedia di portal BKK',
                'content' => 'Dengan senang hati kami umumkan bahwa proses seleksi pekerjaan batch 2 telah selesai. Hasilnya dapat dilihat melalui portal BKK dengan login menggunakan akun siswa Anda. Bagi yang lolos akan mendapatkan email pemberitahuan dari perusahaan terkait. Silakan hubungi koordinator BKK bila ada pertanyaan lebih lanjut.',
                'image' => null,
                'author_id' => 1,
                'published_at' => now()->subDays(10),
                'is_published' => true,
            ],
            [
                'title' => 'Workshop Skills Interview dan CV Writing',
                'slug' => 'workshop-skills-interview-cv-writing',
                'category' => 'Workshop',
                'excerpt' => 'Bergabunglah dengan workshop gratis kami untuk meningkatkan skill interview dan pembuatan CV',
                'content' => 'BKK mengadakan workshop gratis tentang skill interview dan pembuatan CV yang efektif. Workshop ini akan dipandu oleh praktisi HR berpengalaman dari berbagai perusahaan. Peserta akan belajar tentang teknik presentasi diri, cara menjawab pertanyaan interview, dan membuat CV yang menarik. Daftar sekarang untuk mendapatkan tempat terbatas! Workshop dijadwalkan pada tanggal 15 April 2026.',
                'image' => null,
                'author_id' => 1,
                'published_at' => now()->subDays(15),
                'is_published' => true,
            ],
            [
                'title' => 'Update Lowongan Pekerjaan Terbaru April 2026',
                'slug' => 'update-lowongan-pekerjaan-terbaru-april-2026',
                'category' => 'Lowongan',
                'excerpt' => 'Tambahan 25 lowongan pekerjaan dari berbagai industri telah ditambahkan ke portal',
                'content' => 'Bulan April ini kami telah menambahkan 25 lowongan pekerjaan dari berbagai industri termasuk manufaktur, IT, hospitality, dan jasa. Semua lowongan dapat diakses melalui menu "Cari Lowongan" di portal BKK. Setiap lowongan memiliki deskripsi lengkap, syarat dan ketentuan, serta informasi kontak perusahaan. Segera daftarkan lamaran Anda sebelum kuota terpenuhi!',
                'image' => null,
                'author_id' => 1,
                'published_at' => now()->subDays(20),
                'is_published' => true,
            ],
            [
                'title' => 'Tips Sukses Melamar Pekerjaan di Era Digital',
                'slug' => 'tips-sukses-melamar-pekerjaan-di-era-digital',
                'category' => 'Tips Karir',
                'excerpt' => 'Panduan lengkap tentang cara melamar pekerjaan secara online dengan efektif',
                'content' => 'Proses pelamaran pekerjaan di era digital memiliki beberapa perbedaan dengan cara tradisional. Berikut adalah beberapa tips untuk meningkatkan peluang Anda diterima: 1) Siapkan CV dan surat lamaran yang profesional, 2) Pahami dengan detail deskripsi pekerjaan dan syarat yang diminta, 3) Customize lamaran Anda untuk setiap posisi, 4) Gunakan bahasa yang profesional dan lugas, 5) Follow-up dengan profesional setelah mengirim lamaran, 6) Manfaatkan media sosial profesional seperti LinkedIn. Semoga tips ini bermanfaat!',
                'image' => null,
                'author_id' => 1,
                'published_at' => now()->subDays(25),
                'is_published' => true,
            ],
        ];

        foreach ($newsItems as $item) {
            News::firstOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
