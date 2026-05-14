<?php

foreach (['/tmp/views', '/tmp/cache', '/tmp/logs', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

$root = __DIR__ . '/..';
chdir($root);
define('LARAVEL_START', microtime(true));

require $root . '/vendor/autoload.php';

$app = require_once $root . '/bootstrap/app.php';

// Debug: cek apakah view ter-bind
echo 'view bound: ' . ($app->bound('view') ? 'YES' : 'NO') . '<br>';
echo 'providers file: ' . (file_exists($root.'/bootstrap/providers.php') ? 'YES' : 'NO') . '<br>';

// Cek packages.php cache
echo 'packages cache: ' . (file_exists('/tmp/cache/packages.php') ? 'YES' : 'NO') . '<br>';

// Boot aplikasi manual
$app->boot();
echo 'after boot - view bound: ' . ($app->bound('view') ? 'YES' : 'NO') . '<br>';

// List semua registered service providers
$providers = array_keys($app->getLoadedProviders());
echo '<pre>';
echo "Loaded providers:\n";
foreach ($providers as $p) {
    echo "  - $p\n";
}
echo '</pre>';