<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Exception; // Tambahkan ini di atas

class SupabaseStorageService
{
    protected string $url;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        // Berikan default string kosong '' agar tidak jadi null
        $this->url    = rtrim(env('SUPABASE_URL', ''), '/');
        $this->key    = env('SUPABASE_KEY', '');
        $this->bucket = env('SUPABASE_BUCKET', 'cv-applications');

        // (Opsional tapi sangat disarankan) Kasih peringatan jelas kalau key belum diisi
        if (empty($this->key) || empty($this->url)) {
            // Ini akan memunculkan error yang lebih mudah dipahami olehmu jika env lupa diisi
            throw new Exception("Konfigurasi SUPABASE_URL atau SUPABASE_KEY belum diisi di file .env atau Vercel!");
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