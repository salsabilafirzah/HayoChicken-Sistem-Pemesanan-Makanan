<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\1 = App\Models\User::where('role', 'SELLER')->first();
\   BadMethodCallException  Call to undefined method App\Models\User::createToken(). = \1->createToken('test')->plainTextToken;
echo 'TOKEN: ' . \   BadMethodCallException  Call to undefined method App\Models\User::createToken(). . PHP_EOL;
\ = curl_init('http://127.0.0.1:8000/api/v1/seller/orders');
curl_setopt(\, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . \   BadMethodCallException  Call to undefined method App\Models\User::createToken()., 'Accept: application/json']);
curl_setopt(\, CURLOPT_RETURNTRANSFER, true);
\ = curl_exec(\);
\ = curl_getinfo(\, CURLINFO_HTTP_CODE);
echo 'ORDERS HTTP: ' . \ . PHP_EOL;
\ = curl_init('http://127.0.0.1:8000/api/v1/seller/analytics/summary');
curl_setopt(\, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . \   BadMethodCallException  Call to undefined method App\Models\User::createToken()., 'Accept: application/json']);
curl_setopt(\, CURLOPT_RETURNTRANSFER, true);
\ = curl_exec(\);
\ = curl_getinfo(\, CURLINFO_HTTP_CODE);
echo 'ANALYTICS HTTP: ' . \ . PHP_EOL;

