<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Get all favorites for current user.
     */
    public function index()
    {
        $favorites = Favorite::with('product')
            ->where('user_id', Auth::id())
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    /**
     * Toggle favorite status (Add/Remove).
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $validated['product_id'];

        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorite = false;
            $message = 'Produk dihapus dari favorit.';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $isFavorite = true;
            $message = 'Produk ditambahkan ke favorit!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $isFavorite
        ]);
    }
}
