<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    protected $fillable = [
        'student_id', 'user_id',
        // Identitas
        'nama_lengkap', 'nik', 'tempat_lahir', 'tanggal_lahir',
        'alamat_lengkap', 'no_hp', 'email',
        // Sekolah
        'tahun_lulus', 'jurusan',
        // Status
        'status_saat_ini',
        // Pekerjaan
        'lokasi_kerja', 'nama_instansi', 'alamat_perusahaan',
        'posisi_jabatan', 'tmt_bekerja', 'range_gaji',
        'pendapatan_bulanan', 'keselarasan_jurusan',
        // Kuliah
        'status_pt', 'nama_pt', 'jurusan_pt', 'jenjang_kuliah', 'tmt_kuliah',
        // Wirausaha
        'nama_usaha', 'status_usaha', 'tmt_wirausaha', 'omzet_per_bulan',
        // Belum Bekerja
        'detail_kegiatan', 'detail_kegiatan_lainnya',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt_bekerja'   => 'date',
        'tmt_kuliah'    => 'date',
        'tmt_wirausaha' => 'date',
        'is_read' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}