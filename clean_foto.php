<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$students = App\Models\Student::whereNotNull('profile_picture')->get();
foreach($students as $s) {
    $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($s->profile_picture);
    if(!$exists) {
        echo "HAPUS path: ".$s->profile_picture." (". $s->full_name.")\n";
        $s->update(['profile_picture' => null]); // uncomment kalau mau bersihkan
    }
}
