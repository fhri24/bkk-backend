<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

echo 'app OK<br>';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo 'kernel OK<br>';
    
    $request = Illuminate\Http\Request::capture();
    echo 'request OK<br>';
    
    $response = $kernel->handle($request);
    echo 'handle OK<br>';
    
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    echo '<pre>';
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . "\n";
    echo 'Line: ' . $e->getLine() . "\n";
    echo $e->getTraceAsString();
    echo '</pre>';
}