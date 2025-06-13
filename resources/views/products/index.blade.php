@extends('layouts.app')

@section('title', 'Products')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold mb-0">Shop Our Products</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Sidebar with filters -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filters</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Categories</h6>
                    <div class="list-group list-group-flush border-0">
                        <a href="{{ url('/products') }}" class="list-group-item list-group-item-action border-0 rounded {{ !request('category') ? 'active' : '' }}">
                            <i class="bi bi-grid me-2"></i>All Categories
                            <span class="badge bg-secondary float-end">{{ App\Models\Product::where('active', true)->count() }}</span>
                        </a>
                        @foreach($categories as $category)
                            @php
                                $categoryIcons = [
                                    'Electronics' => 'bi-laptop',
                                    'Clothing' => 'bi-bag',
                                    'Books' => 'bi-book',
                                    'Home & Kitchen' => 'bi-house',
                                    'Sports & Outdoors' => 'bi-bicycle',
                                ];
                                $icon = $categoryIcons[$category->name] ?? 'bi-tag';
                                $productCount = App\Models\Product::where('category_id', $category->id)->where('active', true)->count();
                            @endphp
                            <a href="{{ url('/products?category=' . $category->name) }}"
                               class="list-group-item list-group-item-action border-0 rounded {{ request('category') == $category->name ? 'active' : '' }}">
                                <i class="bi {{ $icon }} me-2"></i>{{ $category->name }}
                                <span class="badge bg-secondary float-end">{{ $productCount }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-sort-down me-2"></i>Sort By</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush border-0">
                        <a href="{{ url('/products?' . http_build_query(array_merge(request()->except('sort'), ['sort' => 'newest']))) }}"
                           class="list-group-item list-group-item-action border-0 rounded {{ request('sort') == 'newest' ? 'active' : '' }}">
                            <i class="bi bi-calendar-plus me-2"></i>Newest First
                        </a>
                        <a href="{{ url('/products?' . http_build_query(array_merge(request()->except('sort'), ['sort' => 'price_low']))) }}"
                           class="list-group-item list-group-item-action border-0 rounded {{ request('sort') == 'price_low' ? 'active' : '' }}">
                            <i class="bi bi-sort-numeric-down me-2"></i>Price: Low to High
                        </a>
                        <a href="{{ url('/products?' . http_build_query(array_merge(request()->except('sort'), ['sort' => 'price_high']))) }}"
                           class="list-group-item list-group-item-action border-0 rounded {{ request('sort') == 'price_high' ? 'active' : '' }}">
                            <i class="bi bi-sort-numeric-up me-2"></i>Price: High to Low
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products grid -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $products->total() }} Products Found</h5>
                            @if(request('category'))
                                <p class="text-muted mb-0">Category: {{ request('category') }}</p>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-muted">View:</span>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary active" data-bs-toggle="tooltip" title="Grid View">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="List View">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="alert alert-info shadow-sm">
                    <i class="bi bi-info-circle-fill me-2"></i>No products found. Try a different category or check back later.
                </div>
            @else
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" class="card-img-top" alt="{{ $product->name }}">
                                    @endif

                                    @if($product->featured)
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <span class="badge bg-primary">Featured</span>
                                        </div>
                                    @endif

                                    @if($product->quantity <= 0)
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-danger">Out of Stock</span>
                                        </div>
                                    @elseif($product->quantity < 5)
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">{{ $product->category ? $product->category->name : 'Uncategorized' }}</span>
                                        <span class="text-warning">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </span>
                                    </div>
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="price">${{ number_format($product->price, 2) }}</span>
                                        <div>
                                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>Details
                                            </a>
                                            @auth
                                                @if($product->quantity > 0)
                                                    <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-cart-plus me-1"></i>Add
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Recently Viewed Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Recently Viewed</h3>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card product-card h-100 border-0 shadow-sm">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" class="card-img-top" alt="Product">
                <div class="card-body">
                    <h5 class="card-title">Sample Product</h5>
                    <p class="card-text text-muted small">Electronics</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price">$99.99</span>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card product-card h-100 border-0 shadow-sm">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" class="card-img-top" alt="Product">
                <div class="card-body">
                    <h5 class="card-title">Sample Product</h5>
                    <p class="card-text text-muted small">Clothing</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price">$49.99</span>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card product-card h-100 border-0 shadow-sm">
                <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" class="card-img-top" alt="Product">
                <div class="card-body">
                    <h5 class="card-title">Sample Product</h5>
                    <p class="card-text text-muted small">Books</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price">$19.99</span>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card product-card h-100 border-0 shadow-sm">
                <img src="https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1332&q=80" class="card-img-top" alt="Product">
                <div class="card-body">
                    <h5 class="card-title">Sample Product</h5>
                    <p class="card-text text-muted small">Home & Kitchen</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price">$79.99</span>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection

@endsection
