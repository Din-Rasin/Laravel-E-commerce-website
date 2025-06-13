@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">
                    User Menu
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ url('/user/profile') }}" class="list-group-item list-group-item-action">Profile</a>
                    <a href="{{ url('/user/orders') }}" class="list-group-item list-group-item-action active">My Orders</a>
                    <a href="{{ url('/cart') }}" class="list-group-item list-group-item-action">My Cart</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order #{{ $order->id }}</h4>
                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm">Back to Orders</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
                            <p>
                                <strong>Status:</strong>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Shipping Information</h5>
                            <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
                            <p><strong>City:</strong> {{ $order->shipping_city }}</p>
                            <p><strong>State:</strong> {{ $order->shipping_state }}</p>
                            <p><strong>Zip Code:</strong> {{ $order->shipping_zipcode }}</p>
                            <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                        </div>
                    </div>

                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px;">
                                                @else
                                                    <img src="https://via.placeholder.com/60x60" alt="Product Image" class="img-thumbnail me-3" style="width: 60px;">
                                                @endif
                                                <div>
                                                    @if($item->product)
                                                        <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none">{{ $item->product->name }}</a>
                                                    @else
                                                        <span>Product no longer available</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>${{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="mt-4">
                            <h5>Order Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{ $order->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
