@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/products') }}">Products</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ url('/products?category=' . $product->category_id) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="row mb-5">
        <div class="col-md-5">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/600x400" class="img-fluid rounded" alt="{{ $product->name }}">
            @endif
        </div>
        <div class="col-md-7">
            <h1 class="mb-3">{{ $product->name }}</h1>
            <p class="text-muted">Category: {{ $product->category ? $product->category->name : 'Uncategorized' }}</p>
            <p class="h3 text-primary mb-4">${{ number_format($product->price, 2) }}</p>

            <div class="mb-4">
                <h5>Description</h5>
                <p>{{ $product->description }}</p>
            </div>

            <div class="mb-4">
                <h5>Availability</h5>
                @if($product->quantity > 0)
                    <p class="text-success">In Stock ({{ $product->quantity }} available)</p>
                @else
                    <p class="text-danger">Out of Stock</p>
                @endif
            </div>

            @if($product->quantity > 0)
                @auth
                    <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="quantity" class="col-form-label">Quantity:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">Register</a> to add this product to your cart.
                    </div>
                @endauth
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">Related Products</h3>
            </div>

            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($relatedProduct->image)
                            <img src="{{ asset($relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="{{ $relatedProduct->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <p class="card-text">{{ Str::limit($relatedProduct->description, 50) }}</p>
                            <p class="card-text text-primary fw-bold">${{ number_format($relatedProduct->price, 2) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
