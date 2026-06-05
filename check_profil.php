<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'Muhammad Faisal Nur Karim')->first();
echo "userable: ".get_class($user->userable ?? new stdClass).PHP_EOL;
echo "student: ".($user->student ? get_class($user->student) : 'null').PHP_EOL;
$profil = $user->userable ?? $user->student;
echo "profil class: ".($profil ? get_class($profil) : 'null').PHP_EOL;
echo "is Student: ".($profil instanceof App\Models\Student ? 'YA' : 'TIDAK').PHP_EOL;
