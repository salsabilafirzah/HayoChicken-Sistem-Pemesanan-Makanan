<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class AnalyticsOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'CUSTOMER')->first();
        if (!$customer) {
            $customer = User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567899',
                'password_hash' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'CUSTOMER'
            ]);
        }

        $products = Product::all();
        if ($products->isEmpty()) return;

        // Create orders for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // 2-3 orders per day
            for ($j = 0; $j < rand(2, 3); $j++) {
                $orderTime = $date->copy()->hour(rand(10, 20))->minute(rand(0, 59));
                
                $orderNumber = "HC-" . $orderTime->format('Ymd') . "-" . str_pad(rand(1, 99), 4, '0', STR_PAD_LEFT);
                
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $customer->id,
                    'status' => 'DONE',
                    'delivery_address' => 'Sample Address ' . ($i + 1),
                    'payment_method' => rand(0, 1) ? 'CASH' : 'QRIS_MANUAL',
                    'total_amount' => 0,
                    'created_at' => $orderTime,
                    'updated_at' => $orderTime,
                ]);

                $total = 0;
                // 1-2 items per order
                $selectedProducts = $products->random(rand(1, 2));
                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 3);
                    $subtotal = $product->base_price * $qty;
                    $total += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name_snapshot' => $product->name,
                        'quantity' => $qty,
                        'price_at_order' => $product->base_price,
                        'subtotal' => $subtotal,
                        'created_at' => $orderTime,
                        'updated_at' => $orderTime,
                    ]);
                }
                
                $order->update(['total_amount' => $total]);
            }
        }

        // Add 2 NEW/PENDING orders for today
        for ($k = 0; $k < 2; $k++) {
            $order = Order::create([
                'order_number' => "HC-" . now()->format('Ymd') . "-MOD-" . ($k + 1),
                'user_id' => $customer->id,
                'status' => $k == 0 ? 'NEW' : 'PENDING_VERIFICATION',
                'delivery_address' => 'Modern Complex A',
                'payment_method' => $k == 0 ? 'CASH' : 'QRIS_MANUAL',
                'total_amount' => 25000,
                'created_at' => now(),
            ]);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $products->first()->id,
                'product_name_snapshot' => $products->first()->name,
                'quantity' => 2,
                'price_at_order' => $products->first()->base_price,
                'subtotal' => 25000,
            ]);
        }
    }
}
