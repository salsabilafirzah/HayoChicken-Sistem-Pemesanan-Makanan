<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;

class BuyerController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::orderBy('sort_order')->get();
        
        $query = Product::where('is_available', 1);

        // Filter by Search (LIKE)
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by Category (slug)
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->get()->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'description' => \Illuminate\Support\Str::limit($p->description, 30),
                'base_price' => $p->base_price,
                'image_url' => $p->image_url ?: '/assets/fried_chicken.png'
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

    public function orderSuccess()
    {
        return view('buyer.order-success');
    }

    public function orderStatus()
    {
        return view('buyer.order-status');
    }

    public function orderHistory()
    {
        return view('buyer.order-history');
    }

    public function orderActive()
    {
        return view('buyer.order-active');
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
