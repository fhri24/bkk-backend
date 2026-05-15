<?php

foreach (['/tmp/views', '/tmp/cache', '/tmp/logs', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

$root = __DIR__ . '/..';

// Symlink bootstrap/cache ke /tmp/cache
$bootstrapCache = $root . '/bootstrap/cache';
if (is_dir($bootstrapCache) && !is_link($bootstrapCache)) {
    // Pindahkan isi yang ada ke /tmp/cache
    foreach (glob($bootstrapCache . '/*') as $file) {
        $dest = '/tmp/cache/' . basename($file);
        if (!file_exists($dest)) copy($file, $dest);
    }
    // Hapus directory asli dan buat symlink
    array_map('unlink', glob($bootstrapCache . '/*'));
    rmdir($bootstrapCache);
    symlink('/tmp/cache', $bootstrapCache);
} elseif (!file_exists($bootstrapCache)) {
    symlink('/tmp/cache', $bootstrapCache);
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