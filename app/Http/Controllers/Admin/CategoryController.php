<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%');
            });
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10);

        // Preserve search query in pagination
        if ($request->has('search')) {
            $categories->appends(['search' => $request->search]);
        }

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * API endpoint to get all categories for Postman testing.
     */
    public function apiIndex(Request $request)
    {
        $query = Category::withCount('products');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get all categories or paginated results
        if ($request->has('paginate') && $request->paginate === 'false') {
            $categories = $query->orderBy('id', 'desc')->get();
        } else {
            $categories = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 10));
        }

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories,
            'total_count' => Category::count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'description' => 'nullable|string',
            ]);

            $data = $request->all();
            $data['slug'] = Str::slug($request->name);

            Category::create($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.categories.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'There was an error creating the category. Please check the form and try again.')
                ->with('openAddCategoryModal', true);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $products = $category->products()->paginate(10);
        return view('admin.categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
            ]);

            $data = $request->all();
            $data['slug'] = Str::slug($request->name);

            $category->update($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.categories.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'There was an error updating the category. Please check the form and try again.')
                ->with('openEditCategoryModal', $category->id);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category because it has associated products.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
