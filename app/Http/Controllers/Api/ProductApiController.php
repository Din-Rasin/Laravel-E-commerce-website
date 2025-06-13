<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Get all products.
     */
    public function index()
    {
        $products = Product::with('category')->where('active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get a specific product.
     */
    public function show(Product $product)
    {
        if (!$product->active) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product->load('category')
        ]);
    }
}
