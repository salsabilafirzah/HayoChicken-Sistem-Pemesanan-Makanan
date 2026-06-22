<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$data = App\Models\CartItem::with('product')->get()->toArray();
file_put_contents('dump.json', json_encode($data, JSON_PRETTY_PRINT));
echo "DONE";
