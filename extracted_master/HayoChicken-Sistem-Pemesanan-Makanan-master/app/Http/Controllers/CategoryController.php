<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Category::orderBy('sort_order')->get()
        ]);
    }
}
