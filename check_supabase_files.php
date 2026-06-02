<?php
require 'vendor/autoload.php';

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;

// Check database records
echo "=== Database CV Files ===\n";
$applications = JobApplication::whereNotNull('additional_file')->get(['job_application_id', 'additional_file', 'created_at']);
foreach ($applications as $app) {
    $url = Storage::disk('public')->url('cv_applications/' . $app->additional_file);
    echo "ID: {$app->job_application_id} | File: {$app->additional_file} | Created: {$app->created_at}\n";
    echo "URL: {$url}\n";
    
    // Check if file exists on disk
    $exists = Storage::disk('public')->exists('cv_applications/' . $app->additional_file);
    echo "Exists: " . ($exists ? "YES" : "NO") . "\n\n";
}

// Check what files actually exist in Supabase
echo "=== Files in Supabase ===\n";
try {
    $files = Storage::disk('public')->listContents('cv_applications', false);
    foreach ($files as $file) {
        echo "File: {$file['path']} | Type: {$file['type']}\n";
    }
} catch (\Exception $e) {
    echo "Error listing files: " . $e->getMessage() . "\n";
}
