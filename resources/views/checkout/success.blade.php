@extends('layouts.app')

@section('title', __('messages.order_confirmed'))

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Compact Order Success Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-center">{{ __('messages.order_confirmed') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="success-icon mb-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <p class="lead">{{ __('messages.order_success') }}</p>
                    </div>

                    <div class="order-info mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('messages.order_number') }} #</span>
                            <span class="fw-bold">{{ $order->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('messages.order_date') }}:</span>
                            <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('messages.order_status') }}:</span>
                            <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ __('messages.total') }}:</span>
                            <span class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="order-details mb-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">{{ __('messages.order_details') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.product') }}</th>
                                        <th class="text-center">{{ __('messages.quantity') }}</th>
                                        <th class="text-end">{{ __('messages.subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product ? $item->product->name : __('messages.product_no_longer_available') }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">{{ __('messages.total') }}:</th>
                                        <th class="text-end">${{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="info-box">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-truck me-2"></i>{{ __('messages.shipping_info') }}
                                </h6>
                                <div class="small">
                                    <div>{{ $order->shipping_address }}</div>
                                    <div>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}</div>
                                    <div>{{ $order->shipping_phone }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-credit-card me-2"></i>{{ __('messages.payment_info') }}
                                </h6>
                                <div class="small">
                                    <div>{{ ucfirst($order->payment_method) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.orders') }}" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="bi bi-list-ul me-1"></i>{{ __('messages.view_all_orders') }}
                        </a>
                        <a href="{{ url('/products') }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
                            <i class="bi bi-cart me-1"></i>{{ __('messages.continue_shopping') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-icon {
        font-size: 3rem;
        color: #28a745;
    }

    .order-info {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .info-box {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        height: 100%;
    }
</style>
@endsection
