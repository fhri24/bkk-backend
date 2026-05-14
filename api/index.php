<?php

foreach (['/tmp/views', '/tmp/cache', '/tmp/logs', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

// Generate packages.php dan tampilkan isinya
$manifest = new \Illuminate\Foundation\PackageManifest(
    new \Illuminate\Filesystem\Filesystem,
    $root,
    '/tmp/cache/packages.php'
);
$manifest->build();

echo '<pre>';
echo "packages.php exists: " . (file_exists('/tmp/cache/packages.php') ? 'YES' : 'NO') . "\n";
echo "packages.php content:\n";
print_r(require '/tmp/cache/packages.php');
echo '</pre>';
die();