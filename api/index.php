<?php

// Set working directory ke root Laravel
$root = __DIR__ . '/..';
chdir($root);

// Bootstrap Laravel
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);