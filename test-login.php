<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $user = \App\Models\User::where('email', 'admin@hayochicken.com')->first();
    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
    $rawRefreshToken = \Illuminate\Support\Str::random(64);
    \App\Models\RefreshToken::create([
        'user_id' => $user->id,
        'token_hash' => hash('sha256', $rawRefreshToken),
        'expires_at' => now()->addDays(7),
    ]);
    echo "SUCCESS";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
