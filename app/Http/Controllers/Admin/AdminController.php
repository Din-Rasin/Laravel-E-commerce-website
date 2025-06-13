<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in the route definition
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Get total counts
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();

        // Calculate total revenue
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // Get recent orders with user information
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Get monthly revenue data for chart
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = Order::where('status', 'completed')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->sum('total_amount');
        }

        // Get revenue by category for pie chart
        $categoryRevenue = [];

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'recentUsers',
            'monthlyRevenue',
            'categoryRevenue'
        ));
    }
}
