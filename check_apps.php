<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'Muhammad Faisal Nur Karim')->first();
echo "User email: ".$user->email.PHP_EOL;
echo "Role: ".$user->role->name.PHP_EOL;

$apps = App\Models\JobApplication::where('email', $user->email)->get();
echo "Lamaran ditemukan: ".$apps->count().PHP_EOL;
