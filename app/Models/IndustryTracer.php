<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndustryTracer extends Model
{
    protected $fillable = [
        'user_id',
        // ✅ student_id dihapus — kolom tidak ada di tabel
        'nama_perusahaan', 'jenis_perusahaan', 'alamat_perusahaan', 'bisnis_utama',
        'nama_responden', 'jabatan_responden', 'email_responden',
        'nilai_integritas', 'nilai_keahlian', 'nilai_bahasa_inggris',
        'nilai_teknologi', 'nilai_komunikasi', 'nilai_kerjasama',
        'nilai_analitis', 'nilai_kepemimpinan', 'nilai_tekanan',
        'saran',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relasi student() dihapus — tidak ada foreign key ke students

    public function getRataRataAttribute(): float
    {
        $values = array_filter([
            $this->nilai_integritas, $this->nilai_keahlian,
            $this->nilai_bahasa_inggris, $this->nilai_teknologi,
            $this->nilai_komunikasi, $this->nilai_kerjasama,
            $this->nilai_analitis, $this->nilai_kepemimpinan,
            $this->nilai_tekanan,
        ]);
        return count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0;
    }
}