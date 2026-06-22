<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$orders = App\Models\Order::with(["orderItems", "user"])->orderBy("created_at", "DESC")->get();
echo "TYPE: " . gettype($orders) . PHP_EOL;
echo "COUNT: " . count($orders) . PHP_EOL;
$json = response()->json(["success" => true, "data" => $orders])->getContent();
echo substr($json, 0, 500) . PHP_EOL;

