<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in the route definition
    }

    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show($token)
    {
        $user = auth()->user();
        $order = Order::where('token', $token)->firstOrFail();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        return view('user.orders.show', compact('order'));
    }
}
