<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$students = App\Models\Student::whereNotNull('profile_picture')->take(3)->get();
foreach($students as $s) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($s->profile_picture);
    echo $s->full_name." => ".$url.PHP_EOL;
}
