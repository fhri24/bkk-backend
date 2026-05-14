<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Test 1: Cek autoload ada tidak
$root = __DIR__ . '/..';

if (!file_exists($root . '/vendor/autoload.php')) {
    die('ERROR: vendor/autoload.php tidak ditemukan!');
}

echo 'vendor OK<br>';

// Test 2: Cek autoload bisa di-load
require $root . '/vendor/autoload.php';
echo 'autoload OK<br>';

// Test 3: Cek bootstrap
if (!file_exists($root . '/bootstrap/app.php')) {
    die('ERROR: bootstrap/app.php tidak ditemukan!');
}

echo 'bootstrap file OK<br>';

chdir($root);
define('LARAVEL_START', microtime(true));

$app = require_once $root . '/bootstrap/app.php';
echo 'app OK<br>';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);