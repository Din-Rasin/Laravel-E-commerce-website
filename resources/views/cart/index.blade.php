@extends('layouts.app')

@section('title', __('messages.your_cart'))

@section('content')
<div class="container">
    <h1 class="mb-4">{{ __('messages.your_cart') }}</h1>

    @if(!$cart || $cart->items->isEmpty())
        <div class="alert alert-info">
            {{ __('messages.cart_empty') }} <a href="{{ url('/products') }}">{{ __('messages.continue_shopping') }}</a>.
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.cart_items') }} ({{ $cart->items->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.product') }}</th>
                                        <th>{{ __('messages.price') }}</th>
                                        <th>{{ __('messages.quantity') }}</th>
                                        <th>{{ __('messages.subtotal') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->image)
                                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px;">
                                                    @else
                                                        <img src="https://via.placeholder.com/60x60" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px;">
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none">{{ $item->product->name }}</a>
                                                        <div class="small text-muted">{{ $item->product->category->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->product->price, 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}" class="form-control form-control-sm" style="width: 70px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">{{ __('messages.update_cart') }}</button>
                                                </form>
                                            </td>
                                            <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.remove') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">{{ __('messages.clear_cart') }}</button>
                        </form>
                        <a href="{{ url('/products') }}" class="btn btn-outline-primary">{{ __('messages.continue_shopping') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.order_summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('messages.subtotal') }}:</span>
                            <span>${{ number_format($cart->getTotalPrice(), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('messages.shipping') }}:</span>
                            <span>{{ __('messages.free') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span>{{ __('messages.total') }}:</span>
                            <span>${{ number_format($cart->getTotalPrice(), 2) }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">{{ __('messages.proceed_to_checkout') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
