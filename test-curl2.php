<?php
$ch = curl_init('http://127.0.0.1:8000/api/v1/auth/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => 'admin@hayochicken.com', 'password' => 'wrong']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
$resp = curl_exec($ch);
echo "RESPONSE_WRONG: $resp\n";

$ch2 = curl_init('http://127.0.0.1:8000/api/v1/auth/login');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode(['email' => 'admin@hayochicken.com', 'password' => 'password123']));
curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
$resp2 = curl_exec($ch2);
echo "RESPONSE_RIGHT: " . substr($resp2, 0, 50) . "...\n";
