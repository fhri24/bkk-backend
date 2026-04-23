<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'content',
        'excerpt',
        'image',
        'tags', // Tambahin ini
        'author_id',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    // Relasi ke User (Penulis)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Logic hitung waktu baca (biar di Blade tinggal manggil $news->reading_time)
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        return $minutes < 1 ? 1 : $minutes;
    }
}
