<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('username', 'admin')->first();
if ($user) {
    echo "User found: " . $user->username . "\n";
    $pass = 'admin1500..';
    if (\Illuminate\Support\Facades\Hash::check($pass, $user->password)) {
        echo "Password MATCH!\n";
    } else {
        echo "Password MISMATCH!\n";
        echo "DB Hash: " . $user->password . "\n";
        echo "Tested with: " . $pass . "\n";
    }
} else {
    echo "User NOT found.\n";
}
