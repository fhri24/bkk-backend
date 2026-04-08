<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    protected $fillable = [
        'student_id', 'status_saat_ini', 'nama_instansi', 
        'tgl_mulai_masuk', 'pendapatan_bulanan', 'keselarasan_jurusan'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}