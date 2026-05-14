<?php

// Buat direktori yang dibutuhkan
foreach (['/tmp/views', '/tmp/cache', '/tmp/logs', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

// Override agar error dikembalikan sebagai JSON, bukan view
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    function ($app) {
        return new class($app) extends \Illuminate\Foundation\Exceptions\Handler {
            public function render($request, \Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }
        };
    }
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);