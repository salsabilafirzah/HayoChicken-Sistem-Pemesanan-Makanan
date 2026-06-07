<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of products. [SEDANG #6]
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_available', true);
        
        // Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('name')->paginate(20)
        ]);
    }

    /**
     * Display the specified product. [SEDANG #6]
     */
    public function show($id)
    {
        $product = Product::with(['category', 'productExtras'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'base_price' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048', // Syarat: jpeg/png, max 2MB
            'is_available' => 'boolean',
        ]);

        $productData = $validated;
        $productData['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $productData['image_url'] = '/storage/' . $path;
        }

        $product = Product::create($productData);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan!',
            'data' => $product
        ], 201);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:150',
            'description' => 'nullable|string',
            'base_price' => 'sometimes|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048', // Syarat: jpeg/png, max 2MB
            'is_available' => 'sometimes|boolean',
        ]);

        $productData = $validated;
        
        if (isset($validated['name'])) {
            $productData['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image_url && !str_starts_with($product->image_url, 'http')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
            }
            
            $path = $request->file('image')->store('products', 'public');
            $productData['image_url'] = '/storage/' . $path;
        }

        $product->update($productData);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui!',
            'data' => $product
        ]);
    }

    /**
     * Toggle availability status.
     */
    public function toggleAvailability(Product $product)
    {
        $product->is_available = !$product->is_available;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Status ketersediaan berhasil diubah!',
            'is_available' => $product->is_available
        ]);
    }

    /**
     * Soft delete the product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus (soft delete)!'
        ]);
    }
}
