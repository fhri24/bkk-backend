<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Exception;

class SupabaseStorageService
{
    protected string $url;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        // Diubah menggunakan config() agar aman dari jebakan cache Laravel di server production
        $this->url    = rtrim(config('services.supabase.url', ''), '/');
        $this->key    = config('services.supabase.key', '');
        $this->bucket = config('services.supabase.bucket', 'bkk-storage');

        // Memastikan variable tidak kosong saat aplikasi berjalan
        if (empty($this->key) || empty($this->url)) {
            throw new Exception("Konfigurasi SUPABASE_URL atau SUPABASE_KEY tidak terbaca oleh sistem! Periksa kembali Environment Variables di Vercel atau file .env kamu.");
        }
    }

    public function upload(UploadedFile $file, string $filename): string|false
    {
        $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$filename}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type'  => $file->getMimeType(),
        ])->withBody(
            file_get_contents($file->getRealPath()),
            $file->getMimeType()
        )->post($endpoint);

        if ($response->successful()) {
            return $filename;
        }

        return false;
    }

    public function getPublicUrl(string $filename): string
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$filename}";
    }

    public function delete(string $filename): bool
    {
        $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$filename}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
        ])->delete($endpoint);

        return $response->successful();
    }
}