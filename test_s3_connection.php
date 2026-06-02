<?php
require 'vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== Filesystem Configuration ===\n";
echo "Default Disk: " . config('filesystems.default') . "\n";
echo "Public Disk Driver: " . config('filesystems.disks.public.driver') . "\n\n";

if (config('filesystems.disks.public.driver') === 's3') {
    echo "=== S3 Configuration ===\n";
    echo "AWS_ACCESS_KEY_ID: " . (env('AWS_ACCESS_KEY_ID') ? 'SET' : 'NOT SET') . "\n";
    echo "AWS_SECRET_ACCESS_KEY: " . (env('AWS_SECRET_ACCESS_KEY') ? 'SET' : 'NOT SET') . "\n";
    echo "AWS_DEFAULT_REGION: " . env('AWS_DEFAULT_REGION') . "\n";
    echo "AWS_BUCKET: " . env('AWS_BUCKET') . "\n";
    echo "AWS_ENDPOINT: " . env('AWS_ENDPOINT') . "\n";
    echo "AWS_USE_PATH_STYLE_ENDPOINT: " . env('AWS_USE_PATH_STYLE_ENDPOINT') . "\n";
    echo "AWS_URL: " . env('AWS_URL') . "\n\n";
    
    // Test connection
    try {
        echo "Testing S3 connection...\n";
        $disk = Storage::disk('public');
        
        // Try to list files
        $files = $disk->listContents('/', false);
        echo "✓ S3 connection successful\n";
        echo "Files in root: " . count(iterator_to_array($files)) . "\n\n";
        
        // Try to create a test file
        echo "Testing file upload...\n";
        $testPath = 'cv_applications/test-upload-' . time() . '.txt';
        $disk->put($testPath, 'Test content');
        echo "✓ Test file uploaded: $testPath\n";
        
        // Check if file exists
        if ($disk->exists($testPath)) {
            echo "✓ File confirmed in S3\n";
        } else {
            echo "✗ File upload reported success but file doesn't exist in S3\n";
        }
        
        // Get file URL
        $url = $disk->url($testPath);
        echo "URL: $url\n\n";
        
    } catch (\Exception $e) {
        echo "✗ S3 Error: " . $e->getMessage() . "\n";
        echo "Exception: " . get_class($e) . "\n";
    }
} else {
    echo "⚠️  S3 not configured - using: " . config('filesystems.disks.public.driver') . "\n";
}
