<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get all cart items for current user.
     */
    public function index()
    {
        $items = CartItem::with('product.productExtras')
            ->where('user_id', Auth::id())
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Add or update an item in the cart.
     */
    public function store(Request $request)
    {
        file_put_contents(public_path('cart_request.txt'), json_encode($request->all(), JSON_PRETTY_PRINT));
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'selected_extras_snapshot' => 'nullable|array',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check availability
        if (!$product->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sedang tidak tersedia.'
            ], 422);
        }

        try {
            $extrasJson = json_encode($validated['selected_extras_snapshot'] ?? []);

            // Cari item dengan kombinasi PERSIS sama: produk + extras yang sama
            $existingItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(selected_extras_snapshot, '$')) = ?", [$extrasJson])
                ->first();

            if ($existingItem) {
                // Tambah quantity (upsert)
                $existingItem->increment('quantity', $validated['quantity']);
                $cartItem = $existingItem->fresh();
            } else {
                // Buat baris baru (extras berbeda = item berbeda)
                $cartItem = CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price_snapshot' => $product->base_price,
                    'selected_extras_snapshot' => $validated['selected_extras_snapshot'] ?? [],
                    'note' => $validated['note'] ?? null,
                    'is_checked' => true,
                ]);
            }
        } catch (\Exception $e) {
            file_put_contents(public_path('cart_error.txt'), $e->getMessage() . "\n\n" . $e->getTraceAsString());
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Item ditambahkan ke keranjang!',
            'data' => $cartItem
        ]);
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'is_checked' => 'nullable|boolean'
        ]);

        $cartItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui!',
            'data' => $cartItem
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang!'
        ]);
    }

    /**
     * Toggle the checked status of a cart item.
     */
    public function toggleCheck(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->is_checked = !$cartItem->is_checked;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pilihan diperbarui!',
            'is_checked' => $cartItem->is_checked
        ]);
    }
}
