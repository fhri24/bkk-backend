<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    // WAJIB ADA: Agar bisa simpan/update data ke database
    protected $fillable = [
        'title',
        'slug',
        'category_id', // Gunakan category_id jika pakai relasi belongsTo
        'content',
        'excerpt',
        'image',
        'author_id',
        'published_at',
        'is_published',
    ];

    // WAJIB ADA: Agar format tanggal d M Y di Blade tidak error
    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * Relasi ke User (Penulis)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Relasi ke Category
     * Pastikan kamu punya model Category dan tabel categories
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}