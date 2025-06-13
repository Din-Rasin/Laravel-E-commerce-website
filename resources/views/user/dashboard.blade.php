@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header">
                    User Menu
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ url('/user/dashboard') }}" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="{{ url('/user/profile') }}" class="list-group-item list-group-item-action">Profile</a>
                    <a href="{{ url('/user/orders') }}" class="list-group-item list-group-item-action">My Orders</a>
                    <a href="{{ url('/cart') }}" class="list-group-item list-group-item-action">My Cart</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>User Dashboard</h4>
                </div>
                <div class="card-body">
                    <h5>Welcome, {{ Auth::user()->name }}!</h5>
                    <p>This is your user dashboard. You can manage your account and view your orders from here.</p>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">My Orders</h5>
                                    <p class="card-text display-4">{{ $totalOrders }}</p>
                                    <a href="{{ url('/user/orders') }}" class="btn btn-light">View Orders</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">My Cart</h5>
                                    <p class="card-text display-4">{{ $cartItemsCount }}</p>
                                    <a href="{{ url('/cart') }}" class="btn btn-light">View Cart</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Recent Orders</h5>
                        @if($recentOrders->isEmpty())
                            <p>You haven't placed any orders yet.</p>
                            <a href="{{ url('/products') }}" class="btn btn-primary">Browse Products</a>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    @if($order->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge bg-info">Processing</span>
                                                    @elseif($order->status == 'completed')
                                                        <span class="badge bg-success">Completed</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.orders.show', $order->token) }}" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
