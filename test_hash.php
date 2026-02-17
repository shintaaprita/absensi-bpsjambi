<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test create with hashed cast
$plain = 'test123';
$u = \App\Models\User::create([
    'name' => 'Test',
    'username' => 'testuser',
    'email' => 'test@example.com',
    'password' => \Illuminate\Support\Facades\Hash::make($plain),
    'nip_lama' => 'test',
]);

echo "Password in DB: " . $u->password . "\n";
if (\Illuminate\Support\Facades\Hash::check($plain, $u->password)) {
    echo "Check SUCCESS\n";
} else {
    echo "Check FAILED\n";
    // Check if it's double hashed
    if (\Illuminate\Support\Facades\Hash::check(\Illuminate\Support\Facades\Hash::make($plain), $u->password)) {
        echo "Double Hashing confirmed!\n";
    }
}
$u->delete();
