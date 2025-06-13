@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<!-- Hero Section -->
<div class="hero-section-gradient mb-5">
    <div class="container">
        <div class="row py-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">{{ __('messages.discover_products') }}</h1>
                <p class="lead mb-4">{{ __('messages.shop_latest_trends') }}</p>
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <a href="{{ url('/products') }}" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-grid me-2"></i>{{ __('messages.browse_products') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Features Section -->
    <div class="row mb-5 text-center">
        <div class="col-12 mb-4">
            <h2 class="fw-bold">{{ __('messages.why_shop_with_us') }}</h2>
            <p class="text-muted">{{ __('messages.best_shopping_experience') }}</p>
        </div>

        <div class="col-md-3 mb-4">
            <div class="p-4">
                <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                    <i class="bi bi-truck"></i>
                </div>
                <h5>{{ __('messages.free_shipping') }}</h5>
                <p class="text-muted small">{{ __('messages.on_orders_over') }}</p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="p-4">
                <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5>{{ __('messages.secure_payments') }}</h5>
                <p class="text-muted small">{{ __('messages.secure_checkout') }}</p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="p-4">
                <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </div>
                <h5>{{ __('messages.easy_returns') }}</h5>
                <p class="text-muted small">{{ __('messages.hassle_free_returns') }}</p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="p-4">
                <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                    <i class="bi bi-headset"></i>
                </div>
                <h5>{{ __('messages.customer_support') }}</h5>
                <p class="text-muted small">{{ __('messages.dedicated_support') }}</p>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">{{ __('messages.featured_products') }}</h2>
            <p class="text-muted">{{ __('messages.handpicked_products') }}</p>
        </div>

        @php
            $featuredProducts = App\Models\Product::where('featured', true)->where('active', true)->take(3)->get();
        @endphp

        @foreach($featuredProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        @if($product->image)
                            <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-primary">Featured</span>
                        </div>
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
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                            <div>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Details
                                </a>
                                @auth
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-cart-plus me-1"></i>Add
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-12 text-center mt-4">
            <a href="{{ url('/products') }}" class="btn btn-outline-primary">
                View All Products <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="row mb-5 py-5 bg-light rounded">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">{{ __('messages.shop_by_category') }}</h2>
            <p class="text-muted">{{ __('messages.browse_categories') }}</p>
        </div>

        @php
            $categories = App\Models\Category::take(4)->get();
            $categoryIcons = [
                'Electronics' => 'bi-laptop',
                'Clothing' => 'bi-bag',
                'Books' => 'bi-book',
                'Home & Kitchen' => 'bi-house',
                'Sports & Outdoors' => 'bi-bicycle',
            ];
        @endphp

        @foreach($categories as $category)
            <div class="col-md-3 mb-4">
                <div class="category-card h-100">
                    <div class="category-icon mb-3">
                        <i class="bi {{ $categoryIcons[$category->name] ?? 'bi-tag' }} fs-1 text-primary"></i>
                    </div>
                    <h5>{{ $category->name }}</h5>
                    <p class="text-muted small mb-3">{{ Str::limit($category->description, 60) }}</p>
                    <a href="{{ url('/products?category=' . $category->id) }}" class="btn btn-sm btn-primary">
                        {{ __('messages.browse_products') }} <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Newsletter Section -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Subscribe to Our Newsletter</h3>
                    <p class="text-muted mb-4">Stay updated with our latest products and exclusive offers.</p>

                    <form class="row g-3">
                        <div class="col-md-8">
                            <input type="email" class="form-control" placeholder="Your email address">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <h3 class="fw-bold mb-3">{{ __('messages.ready_to_shop') }}</h3>
                    <p class="mb-4">{{ __('messages.create_account_access') }}</p>
                    @guest
                        <div>
                            <a href="{{ route('register') }}" class="btn btn-light">
                                <i class="bi bi-person-plus me-2"></i>{{ __('messages.sign_up_now') }}
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light ms-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('messages.login') }}
                            </a>
                        </div>
                    @else
                        <a href="{{ url('/products') }}" class="btn btn-light">
                            <i class="bi bi-grid me-2"></i>{{ __('messages.browse_products') }}
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="row mb-5 py-5 bg-light rounded">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">What Our Customers Say</h2>
            <p class="text-muted">Trusted by thousands of customers worldwide</p>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="rounded-circle me-3" width="60">
                        <div>
                            <h5 class="mb-0">Sarah Johnson</h5>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"I'm extremely satisfied with my purchase. The quality of the products exceeded my expectations, and the delivery was prompt. Will definitely shop here again!"</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/men/44.jpg" alt="Customer" class="rounded-circle me-3" width="60">
                        <div>
                            <h5 class="mb-0">Michael Brown</h5>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"The customer service is outstanding. I had an issue with my order, and they resolved it immediately. The products are high-quality and worth every penny."</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Customer" class="rounded-circle me-3" width="60">
                        <div>
                            <h5 class="mb-0">Emily Davis</h5>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"I've been shopping here for years, and I've never been disappointed. The website is easy to navigate, and the checkout process is seamless. Highly recommended!"</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add custom CSS for feature icons -->
@section('styles')
<style>
    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
    }
</style>
@endsection

@endsection
