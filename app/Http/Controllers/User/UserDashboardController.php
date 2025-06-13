<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in the route definition
    }

    /**
     * Redirect users from dashboard to orders page.
     */
    public function index()
    {
        return redirect()->route('user.orders');
    }
}
