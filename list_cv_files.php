<?php
require 'vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

try {
    $disk = Storage::disk('public');
    
    echo "=== Files in cv_applications folder ===\n";
    $files = $disk->listContents('cv_applications', false);
    
    $fileList = iterator_to_array($files);
    echo "Total files: " . count($fileList) . "\n\n";
    
    foreach ($fileList as $file) {
        echo "- {$file['path']}\n";
    }
    
    if (empty($fileList)) {
        echo "\n⚠️  No files found in cv_applications folder!\n";
        echo "\nThis explains why 404 errors occur - files were recorded in DB but never uploaded.\n";
        echo "\nSolution: Need to re-upload existing CVs or delete orphaned DB records.\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
