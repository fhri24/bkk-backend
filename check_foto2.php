<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$s = App\Models\AlumniStory::with('student')->where('status','approved')->first();
$pic = $s->student->profile_picture ?? null;
echo "Path DB: ".$pic.PHP_EOL;
echo "File ada: ".(file_exists(storage_path('app/public/'.$pic)) ? 'YA' : 'TIDAK').PHP_EOL;
echo "Public URL: ".\Illuminate\Support\Facades\Storage::disk('public')->url($pic).PHP_EOL;
echo "Symlink target: ".readlink(public_path('storage')).PHP_EOL;
