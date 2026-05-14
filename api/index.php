<?php

$runtimeDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/logs',
    '/tmp/sessions',
];

foreach ($runtimeDirs as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

foreach ([
    'APP_CONFIG_CACHE' => '/tmp/cache/config.php',
    'APP_EVENTS_CACHE' => '/tmp/cache/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/cache/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/cache/routes.php',
    'VIEW_COMPILED_PATH' => '/tmp/views',
    'SESSION_PATH' => '/tmp/sessions',
] as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

// Force register semua core service providers
$app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
$app->register(\Illuminate\View\ViewServiceProvider::class);
$app->register(\Illuminate\Translation\TranslationServiceProvider::class);
$app->register(\Illuminate\Validation\ValidationServiceProvider::class);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();

try {
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo '<pre>ERROR: ' . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() . '</pre>';
}