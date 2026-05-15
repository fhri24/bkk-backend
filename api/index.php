<?php

foreach (['/tmp/views', '/tmp/cache', '/tmp/logs', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

$root = __DIR__ . '/..';

// Symlink bootstrap/cache ke /tmp/cache jika memungkinkan
$bootstrapCache = $root . '/bootstrap/cache';
$cacheTarget = '/tmp/cache';

if (!is_dir($cacheTarget)) {
    mkdir($cacheTarget, 0775, true);
}

if (is_link($bootstrapCache)) {
    // Already linked, nothing else to do.
} elseif (is_dir($bootstrapCache) && !is_writable($bootstrapCache)) {
    // Vercel runtime filesystem is read-only under /var/task.
    // Keep the existing bootstrap/cache directory if it already exists.
} elseif (is_dir($bootstrapCache)) {
    // Move existing cache files to /tmp/cache and replace directory with symlink.
    foreach (glob($bootstrapCache . '/*') as $file) {
        $dest = $cacheTarget . '/' . basename($file);
        if (!file_exists($dest)) {
            copy($file, $dest);
        }
    }

    if (is_writable($bootstrapCache)) {
        array_map('unlink', glob($bootstrapCache . '/*'));
        rmdir($bootstrapCache);
        symlink($cacheTarget, $bootstrapCache);
    }
} elseif (!file_exists($bootstrapCache)) {
    symlink($cacheTarget, $bootstrapCache);
}

chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

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