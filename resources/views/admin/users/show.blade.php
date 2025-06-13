@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person me-2"></i>User Details</h1>
    <a href="{{ route('admin.users.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
</div>

<!-- User Information Card -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle mb-3">
                        <span class="avatar-initials">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <h4 class="font-weight-bold">{{ $user->name }}</h4>
                    <p class="text-muted">
                        @if($user->isAdmin())
                            <span class="badge bg-primary">Admin</span>
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </p>
                </div>
                
                <div class="user-details">
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-envelope me-2"></i>Email:</span>
                        <span class="detail-value">{{ $user->email }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-calendar me-2"></i>Joined:</span>
                        <span class="detail-value">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="bi bi-clock me-2"></i>Last Updated:</span>
                        <span class="detail-value">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-8 col-md-6 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="stat-title">TOTAL ORDERS</div>
                                        <div class="stat-value">{{ $user->orders->count() }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-bag stat-icon text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="stat-title">TOTAL SPENT</div>
                                        <div class="stat-value">${{ number_format($user->orders->sum('total_amount'), 2) }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-currency-dollar stat-icon text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="stat-title">LAST ORDER</div>
                                        <div class="stat-value">
                                            @if($user->orders->count() > 0)
                                                {{ $user->orders->sortByDesc('created_at')->first()->created_at->format('M d') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-calendar-check stat-icon text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Orders -->
<div class="card admin-card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-bag me-2"></i>Order History</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
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
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-center py-4">
                                    <i class="bi bi-bag-x" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <p class="text-muted mt-2">No orders found for this user.</p>
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
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }
    
    .avatar-initials {
        color: white;
        font-size: 48px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .user-details {
        margin-top: 20px;
    }
    
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
