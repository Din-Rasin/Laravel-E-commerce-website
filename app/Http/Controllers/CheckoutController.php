<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in the route definition
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check if all products are available in the requested quantities
        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product->active || $product->quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Product '{$product->name}' is not available in the requested quantity.");
            }
        }

        return view('checkout.index', compact('cart'));
    }

    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|in:cash,credit_card',
            'notes' => 'nullable|string'
        ]);

        $user = auth()->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check if all products are available in the requested quantities
        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product->active || $product->quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Product '{$product->name}' is not available in the requested quantity.");
            }
        }

        // Create order
        DB::beginTransaction();

        try {
            $order = new Order([
                'user_id' => $user->id,
                'total_amount' => $cart->getTotalPrice(),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'shipping_phone' => $request->shipping_phone,
                'notes' => $request->notes
            ]);

            $order->save();

            // Create order items and update product quantities
            foreach ($cart->items as $item) {
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                $orderItem->save();

                // Update product quantity
                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();
            }

            // Clear the cart
            CartItem::where('cart_id', $cart->id)->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->token)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('checkout.index')->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    /**
     * Display the order success page.
     */
    public function success($token)
    {
        $user = auth()->user();
        $order = Order::where('token', $token)->firstOrFail();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
