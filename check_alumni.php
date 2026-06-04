<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$stories = App\Models\AlumniStory::with('student')->where('status','approved')->get();
foreach($stories as $s) {
    echo $s->name.' | photo:'.$s->photo.' | student_pic:'.optional($s->student)->profile_picture.PHP_EOL;
}
