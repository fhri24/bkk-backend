<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class SupabaseStorageService
{
    protected string $url;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        $this->url    = rtrim(env('SUPABASE_URL'), '/');
        $this->key    = env('SUPABASE_KEY');
        $this->bucket = env('SUPABASE_BUCKET', 'cv-applications');
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