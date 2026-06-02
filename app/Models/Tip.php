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
        'pro_tips',
        'avoid_mistakes',
        'icon',
        'image',
        'is_featured',
        'is_published',
        'urutan',
    ];

    protected $casts = [
        'is_featured'  => 'integer',
        'is_published' => 'integer',
        'urutan'       => 'integer',
        'pro_tips'     => 'array',
        'avoid_mistakes' => 'array',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($tip) {
            if (empty($tip->slug)) {
                $tip->slug = Str::slug($tip->judul) . '-' . Str::random(5);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

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

    public function steps()
    {
        return $this->hasMany(TipStep::class)->orderBy('step_order');
    }

}
