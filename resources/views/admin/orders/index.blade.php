@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-bag me-2"></i>Orders</h1>
    <form action="{{ route('admin.orders.index') }}" method="GET">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search orders..." name="search" value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Content Row -->
<div class="card admin-card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewOrderModal{{ $order->id }}">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-center py-4">
                                    <i class="bi bi-bag-x" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <p class="text-muted mt-2">No orders found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
    <!-- Order Modals -->
    @foreach($orders as $order)
    <!-- View Order Modal -->
    <div class="modal fade" id="viewOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="viewOrderModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel{{ $order->id }}"><i class="bi bi-bag me-2"></i>Order #{{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <!-- Order Details -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="m-0 font-weight-bold">Order Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="bi bi-calendar me-2"></i>Order Date:</span>
                                        <span class="detail-value">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="bi bi-tag me-2"></i>Order Status:</span>
                                        <span class="detail-value">
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="bi bi-currency-dollar me-2"></i>Total Amount:</span>
                                        <span class="detail-value">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>

                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="status{{ $order->id }}" class="form-label">Update Status</label>
                                            <select class="form-select" id="status{{ $order->id }}" name="status">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="m-0 font-weight-bold">Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="bi bi-person me-2"></i>Name:</span>
                                        <span class="detail-value">{{ $order->user->name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="bi bi-envelope me-2"></i>Email:</span>
                                        <span class="detail-value">{{ $order->user->email }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label"><i class="bi bi-calendar me-2"></i>Customer Since:</span>
                                        <span class="detail-value">{{ $order->user->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <button type="button" class="btn btn-info mt-3" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $order->user->id }}">
                                        <i class="bi bi-person me-1"></i> View Customer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="m-0 font-weight-bold">Order Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="detail-item">
                                        <span class="detail-label">Subtotal:</span>
                                        <span class="detail-value">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Shipping:</span>
                                        <span class="detail-value">$0.00</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Tax:</span>
                                        <span class="detail-value">$0.00</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Discount:</span>
                                        <span class="detail-value">$0.00</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label fw-bold">Total:</span>
                                        <span class="detail-value fw-bold">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Order Items</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}" width="50" height="50" class="img-thumbnail me-3">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                                <i class="bi bi-image text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                            @if($item->product && $item->product->category)
                                                                <small class="text-muted">{{ $item->product->category->name }}</small>
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('styles')
<style>
    .detail-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e3e6f0;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        color: #4e73df;
        display: block;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #5a5c69;
    }
</style>
@endsection
