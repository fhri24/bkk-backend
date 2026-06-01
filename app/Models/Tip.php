<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tip extends Model
{
    use HasFactory;

    protected $table = 'tips';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'ringkasan',
        'konten',
        'icon',
        'is_featured',
        'is_published',
        'urutan',
    ];

    protected $casts = [
        'is_featured'  => 'integer',
        'is_published' => 'integer',
        'urutan'       => 'integer',
    ];

    /**
     * Boot function untuk handling events model
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tip) {
            if (empty($tip->slug)) {
                // Menggunakan random string tambahan agar slug selalu unik
                $tip->slug = Str::slug($tip->judul) . '-' . Str::random(5);
            }
        });
    }

    /**
     * Scope untuk mengambil tips yang sudah dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    /**
     * Scope untuk mengambil tips unggulan (featured)
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    /**
     * Mengambil daftar seluruh kategori yang tersedia
     */
    public static function kategoriList(): array
    {
        return [
            'Interview',
            'Psikotes',
            'CV & Portofolio',
            'Dunia Kerja',
            'Wirausaha',
            'Lainnya',
        ];
    }

    /**
     * Mendapatkan default icon berdasarkan kategori
     */
    public static function defaultIcon(string $kategori): string
    {
        return match ($kategori) {
            'Interview'       => 'fas fa-comments',
            'Psikotes'        => 'fas fa-brain',
            'CV & Portofolio' => 'fas fa-file-alt',
            'Dunia Kerja'     => 'fas fa-briefcase',
            'Wirausaha'       => 'fas fa-store',
            default           => 'fas fa-lightbulb',
        };
    }
}
