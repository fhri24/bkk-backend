<?php

foreach (['/tmp/views', '/tmp/cache', '/tmp/logs', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

// Generate packages.php cache kalau belum ada
if (!file_exists('/tmp/cache/packages.php')) {
    $packageManifest = new \Illuminate\Foundation\PackageManifest(
        new \Illuminate\Filesystem\Filesystem,
        $root,
        '/tmp/cache/packages.php'
    );
    $packageManifest->build();
}

$app = require_once $root . '/bootstrap/app.php';

// Override package manifest path ke /tmp
$app->singleton(\Illuminate\Foundation\PackageManifest::class, function ($app) use ($root) {
    return new \Illuminate\Foundation\PackageManifest(
        new \Illuminate\Filesystem\Filesystem,
        $root,
        '/tmp/cache/packages.php'
    );
});

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();

try {
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo '<pre>ERROR: ' . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() . '</pre>';
}