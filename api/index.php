<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Buat direktori yang dibutuhkan di /tmp
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo '<pre>';
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . "\n";
    echo 'Line: ' . $e->getLine() . "\n";
    echo '</pre>';
}