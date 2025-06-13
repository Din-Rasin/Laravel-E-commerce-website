<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product.category']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%')
                  ->orWhere('total_amount', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($q) use ($searchTerm) {
                      $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(10);

        // Preserve search query in pagination
        if ($request->has('search')) {
            $orders->appends(['search' => $request->search]);
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * API endpoint to get all orders for Postman testing.
     */
    public function apiIndex(Request $request)
    {
        $query = Order::with(['user', 'items.product.category']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%')
                  ->orWhere('total_amount', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($q) use ($searchTerm) {
                      $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Get all orders or paginated results
        if ($request->has('paginate') && $request->paginate === 'false') {
            $orders = $query->orderBy('id', 'desc')->get();
        } else {
            $orders = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 10));
        }

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders,
            'total_count' => Order::count(),
            'pending_count' => Order::where('status', 'pending')->count(),
            'processing_count' => Order::where('status', 'processing')->count(),
            'completed_count' => Order::where('status', 'completed')->count(),
            'cancelled_count' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
            ]);

            $order->update([
                'status' => $request->status,
            ]);

            // Check if the request is coming from the index page modal
            $referer = request()->headers->get('referer');
            if (strpos($referer, 'admin/orders') !== false && !strpos($referer, 'admin/orders/' . $order->id)) {
                return redirect()->route('admin.orders.index')
                    ->with('success', 'Order status updated successfully.');
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
