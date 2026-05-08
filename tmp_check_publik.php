<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$start = microtime(true);
$p = App\Models\Publik::with('user')->latest()->paginate(15);
echo 'count=' . count($p) . "\n";
echo 'time=' . round(microtime(true) - $start, 4) . "\n";
