<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::with('role')->get(['id', 'name', 'email', 'role_id', 'is_active']);

echo "\n=== USERS IN DATABASE ===\n";
foreach ($users as $user) {
    $roleName = $user->role ? $user->role->name : 'NO_ROLE';
    $active = $user->is_active ? 'YES' : 'NO';
    echo "ID: {$user->id} | Email: {$user->email} | Name: {$user->name} | Role: {$roleName} | Active: {$active}\n";
}

// Test password verification
echo "\n=== PASSWORD TEST ===\n";
$admin = \App\Models\User::where('email', 'admin@bkk.com')->first();
if ($admin) {
    $passwordMatch = \Illuminate\Support\Facades\Hash::check('password123', $admin->password);
    echo "Admin user found! Password check (password123): " . ($passwordMatch ? 'MATCH' : 'MISMATCH') . "\n";
} else {
    echo "Admin user NOT FOUND!\n";
}
?>
