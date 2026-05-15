<?php
echo "Testing Supabase connection...\n";

$dsn = 'pgsql:host=aws-1-ap-southeast-1.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require';
$user = 'postgres.eaiwdmemwazqcahokzdg';
$pass = 'BkkBackend17';

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "✓ Connected successfully!\n";
    $result = $pdo->query("SELECT version()");
    echo "✓ PostgreSQL version: " . $result->fetchColumn() . "\n";
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "✗ Code: " . $e->getCode() . "\n";
}
