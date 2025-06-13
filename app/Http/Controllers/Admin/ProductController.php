<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);

        // Preserve search query in pagination
        if ($request->has('search')) {
            $products->appends(['search' => $request->search]);
        }

        $lastProductId = Product::max('id');
        $trashedProducts = Product::onlyTrashed()->with('category')->orderBy('id', 'desc')->get();

        return view('admin.products.index', compact('products', 'lastProductId', 'trashedProducts'));
    }

    /**
     * API endpoint to get all products for Postman testing.
     */
    public function apiIndex(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Get all products or paginated results
        if ($request->has('paginate') && $request->paginate === 'false') {
            $products = $query->orderBy('id', 'desc')->get();
        } else {
            $products = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 10));
        }

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products,
            'total_count' => Product::count(),
            'active_count' => Product::where('active', true)->count(),
            'inactive_count' => Product::where('active', false)->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Product store request', ['request' => $request->all()]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->except('image');

            // Generate slug from name
            $data['slug'] = Str::slug($request->name);

            // Handle image upload
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();

                    \Log::info('Attempting to store image', [
                        'path' => 'public/products/' . $imageName,
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize()
                    ]);

                    // Make sure the directory exists
                    if (!Storage::exists('public/products')) {
                        Storage::makeDirectory('public/products');
                        \Log::info('Created products directory');
                    }

                    // Move the uploaded file directly to the public directory
                    $destinationPath = public_path('storage/products');
                    $image->move($destinationPath, $imageName);

                    \Log::info('Image moved to public directory', [
                        'destination' => $destinationPath . '/' . $imageName
                    ]);

                    // Set the image path for database
                    $data['image'] = 'storage/products/' . $imageName;
                    \Log::info('Image URL set', ['url' => $data['image']]);
                } catch (\Exception $e) {
                    \Log::error('Exception storing image', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $data['image'] = 'images/product-placeholder.jpg';
                }
            } else {
                // Set a default image if none is provided
                $data['image'] = 'images/product-placeholder.jpg';
            }

            // Set featured and active values
            $data['featured'] = $request->has('featured');
            $data['active'] = $request->has('active');

            \Log::info('Creating product', ['data' => $data]);

            $product = Product::create($data);

            \Log::info('Product created', ['product_id' => $product->id]);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            return redirect()->route('admin.products.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'There was an error creating the product. Please check the form and try again.')
                ->with('openAddProductModal', true);
        } catch (\Exception $e) {
            \Log::error('Exception in product creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            \Log::info('Product update request', [
                'product_id' => $product->id,
                'request' => $request->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->except(['image', '_token', '_method']);

            // Generate slug from name
            $data['slug'] = Str::slug($request->name);

            // Handle image upload
            if ($request->hasFile('image')) {
                try {
                    // Delete old image if exists
                    if ($product->image && !Str::contains($product->image, 'placeholder')) {
                        $oldImagePath = public_path($product->image);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                            \Log::info('Deleted old image', ['path' => $oldImagePath]);
                        }
                    }

                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();

                    \Log::info('Attempting to store new image', [
                        'path' => 'storage/products/' . $imageName,
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize()
                    ]);

                    // Move the uploaded file directly to the public directory
                    $destinationPath = public_path('storage/products');
                    $image->move($destinationPath, $imageName);

                    \Log::info('Image moved to public directory', [
                        'destination' => $destinationPath . '/' . $imageName
                    ]);

                    // Set the image path for database
                    $data['image'] = 'storage/products/' . $imageName;
                    \Log::info('Image URL set', ['url' => $data['image']]);
                } catch (\Exception $e) {
                    \Log::error('Exception storing image', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Keep the old image if there's an exception
                    if (!$product->image) {
                        $data['image'] = 'images/product-placeholder.jpg';
                    }
                }
            }

            // Set featured and active values
            $data['featured'] = $request->has('featured');
            $data['active'] = $request->has('active');

            \Log::info('Updating product', ['data' => $data]);

            $product->update($data);

            \Log::info('Product updated', ['product_id' => $product->id]);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in product update', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            return redirect()->route('admin.products.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'There was an error updating the product. Please check the form and try again.')
                ->with('openEditProductModal', $product->id);
        } catch (\Exception $e) {
            \Log::error('Exception in product update', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product moved to trash successfully. You can restore it later if needed.');
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product restored successfully.');
    }

    /**
     * Permanently delete a product.
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // Delete image if exists
        if ($product->image && !Str::contains($product->image, 'placeholder')) {
            $imagePath = public_path($product->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
                \Log::info('Deleted product image', ['path' => $imagePath]);
            }
        }

        $product->forceDelete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product permanently deleted.');
    }
}
