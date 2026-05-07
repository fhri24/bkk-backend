<?php

namespace App\Services;

use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Cache;

class SchoolProfileService
{
    const CACHE_KEY = 'school_profile';
    const CACHE_TTL = 60 * 60 * 24; // 24 jam

    public static function get(): SchoolProfile
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return SchoolProfile::first() ?? new SchoolProfile([
                'name'             => 'BKK SMKN 1 Garut',
                'school_name'      => 'SMKN 1 Garut',
                'school_address'   => 'Jl. Cimanuk No. 309 A, Garut, Jawa Barat',
                'site_title'       => 'Sistem Informasi Bursa Kerja',
                'site_description' => 'Menghubungkan Talenta Alumni SMKN 1 Garut dengan Peluang Karir Masa Depan di Industri Global',
                'tagline'          => 'Garut Bermartabat',
            ]);
        });
    }

    public static function clear(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}