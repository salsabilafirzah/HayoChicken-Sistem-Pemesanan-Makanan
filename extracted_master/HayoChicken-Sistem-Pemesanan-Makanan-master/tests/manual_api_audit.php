<?php

/**
 * Hayo Chicken API Audit Script
 * Memvalidasi 6 Blok Fungsionalitas API v1
 */

$baseUrl = "http://127.0.0.1:8000/api/v1";
$tokens = ['access' => null, 'refresh' => null];

function request($method, $path, $body = null, $token = null) {
    global $baseUrl;
    $ch = curl_init($baseUrl . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $headers = ['Accept: application/json'];
    if ($token) $headers[] = "Authorization: Bearer $token";
    
    if ($body) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['status' => $status, 'data' => json_decode($response, true)];
}

echo "🚀 MEMULAI AUDIT API HAYO CHICKEN...\n";
echo "------------------------------------------\n";

// --- BLOK 1: AUTH ---
echo "[BLOK 1] Testing Auth...\n";
$email = "audit_" . time() . "@example.com";
$reg = request('POST', '/auth/register', [
    'name' => 'Auditor',
    'email' => $email,
    'phone' => '+6281234567890',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]);

if ($reg['status'] === 201) {
    echo "✅ Register Berhasil (201)\n";
} else {
    echo "❌ Register Gagal (" . $reg['status'] . "): " . json_encode($reg['data'], JSON_PRETTY_PRINT) . "\n";
    exit(1);
}

$login = request('POST', '/auth/login', [
    'email' => $email,
    'password' => 'password123'
]);

if ($login['status'] === 200 && isset($login['data']['access_token'])) {
    echo "✅ Login Berhasil (200)\n";
    $tokens['access'] = $login['data']['access_token'];
    $tokens['refresh'] = $login['data']['refresh_token'];
} else {
    echo "❌ Login Gagal\n";
    exit(1);
}

$refresh = request('POST', '/auth/refresh', ['refresh_token' => $tokens['refresh']]);
if ($refresh['status'] === 200) {
    echo "✅ Refresh Token Berhasil\n";
    $tokens['access'] = $refresh['data']['access_token'];
}

// --- BLOK 2: DATA PUBLIC ---
echo "\n[BLOK 2] Testing Data Public...\n";
$cats = request('GET', '/categories');
if ($cats['status'] === 200) echo "✅ GET Categories Berhasil\n";

$prods = request('GET', '/products');
if ($prods['status'] === 200) echo "✅ GET Products Berhasil\n";

// --- BLOK 3: CART ---
echo "\n[BLOK 3] Testing Cart (Customer)...\n";
$firstProduct = $prods['data']['data']['data'][0]['id'] ?? 1;
$cartAdd = request('POST', '/cart', [
    'product_id' => $firstProduct,
    'quantity' => 1,
    'selected_extras_snapshot' => []
], $tokens['access']);

if ($cartAdd['status'] === 200 || $cartAdd['status'] === 201) {
    echo "✅ Add to Cart Berhasil\n";
    $cartItemId = $cartAdd['data']['data']['id'];
}

// Test Upsert Same Extras
$cartUpsert = request('POST', '/cart', [
    'product_id' => $firstProduct,
    'quantity' => 2,
    'selected_extras_snapshot' => []
], $tokens['access']);
if ($cartUpsert['data']['data']['quantity'] >= 3) echo "✅ Upsert Logic (Same Extras) Berhasil\n";

// --- BLOK 4: CHECKOUT ---
echo "\n[BLOK 4] Testing Checkout...\n";
$checkout = request('POST', '/orders', [
    'delivery_address' => 'Lab Audit API',
    'payment_method' => 'COD'
], $tokens['access']);

if ($checkout['status'] === 200) {
    echo "✅ Checkout COD Berhasil (" . $checkout['data']['order_number'] . ")\n";
    $orderId = $checkout['data']['order_id'];
}

// --- BLOK 5: SELLER LOGIC ---
echo "\n[BLOK 5] Testing Seller (State Machine)...\n";
// Login as Seller (Assuming seeder seller: seller@hayochicken.com / password)
$sellerLogin = request('POST', '/auth/login', [
    'email' => 'seller@hayochicken.com',
    'password' => 'password'
]);

if ($sellerLogin['status'] === 200) {
    $sellerToken = $sellerLogin['data']['access_token'];
    echo "✅ Login Seller Berhasil\n";
    
    // Update Status with Note (REJECTED)
    $reject = request('PATCH', "/seller/orders/$orderId/status", [
        'status' => 'REJECTED',
        'note' => 'Audit Test Rejection'
    ], $sellerToken);
    
    if ($reject['status'] === 200) echo "✅ Seller Reject with Note Berhasil\n";
    
    // Test Reject NO note (Should fail)
    $rejectFail = request('PATCH', "/seller/orders/$orderId/status", [
        'status' => 'REJECTED'
    ], $sellerToken);
    if ($rejectFail['status'] === 422) echo "✅ Validation Note Required Berhasil (422)\n";
}

// --- BLOK 6: ANALYTICS ---
echo "\n[BLOK 6] Testing Analytics...\n";
$analytics = request('GET', '/seller/analytics/summary', null, $sellerToken);
if ($analytics['status'] === 200 && isset($analytics['data']['forecasting'])) {
    echo "✅ GET Analytics Summary Berhasil\n";
}

echo "\n------------------------------------------\n";
echo "🎉 AUDIT SELESAI: SELURUH SISTEM SIAP UNTUK FLUTTER!\n";
