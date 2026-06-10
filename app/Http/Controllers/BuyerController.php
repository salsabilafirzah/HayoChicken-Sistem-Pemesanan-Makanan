<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;

class BuyerController extends Controller
{
    public function home(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Buyer home accessed by user: ' . (\Illuminate\Support\Facades\Auth::id() ?: 'GUEST'));
        
        $categories = Category::orderBy('sort_order')->get();
        
        // Ambil semua produk yang tersedia
        // Dan hitung jumlah terjual (quantity) dari pesanan yang sudah ada
        $products = Product::where('is_available', 1)
            ->withCount(['orderItems as sold_total' => function($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw('COALESCE(sum(quantity), 0)'));
            }])
            ->orderByDesc('sold_total') // Urutkan dari yang paling populer
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => \Illuminate\Support\Str::limit($p->description, 45),
                    'base_price' => $p->base_price,
                    'image_url' => $p->image_url ?: '/assets/fried_chicken.png',
                    'sold_total' => (int) $p->sold_total
                ];
            });

        return view('buyer.home', compact('categories', 'products'));
    }

    public function productDetail($id = null)
    {
        $product = Product::with('productExtras')->findOrFail($id);
        return view('buyer.product-detail', compact('product'));
    }

    public function cart()
    {
        return view('buyer.cart');
    }

    public function checkout()
    {
        return view('buyer.checkout');
    }

    public function orderSuccess(\App\Models\Order $order)
    {
        return view('buyer.order-success', compact('order'));
    }

    public function orderStatus(\App\Models\Order $order)
    {
        $order->load(['orderItems', 'statusLogs']);
        return view('buyer.order-status', compact('order'));
    }

    public function orderHistory()
    {
        $orders = \App\Models\Order::with('orderItems')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

        return view('buyer.order-history', compact('orders'));
    }

    public function orderActive()
    {
        $orders = \App\Models\Order::with('orderItems')
            ->where('user_id', auth()->id())
            ->whereNotIn('status', ['DONE', 'REJECTED'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.order-active', compact('orders'));
    }

    public function notifications()
    {
        return view('buyer.notifications');
    }

    public function savedAddresses()
    {
        return view('buyer.address-saved');
    }

    public function addAddress()
    {
        return view('buyer.address-add');
    }
}
