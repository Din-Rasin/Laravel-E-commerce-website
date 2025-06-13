<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icon-css@3.5.0/css/flag-icon.min.css">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-shop me-2"></i>{{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-1"></i>{{ __('messages.home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ url('/products') }}">
                            <i class="bi bi-grid me-1"></i>{{ __('messages.products') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-tags me-1"></i>{{ __('messages.categories') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            @php
                                $categories = App\Models\Category::take(5)->get();
                            @endphp
                            @foreach($categories as $category)
                                <li><a class="dropdown-item" href="{{ url('/products?category=' . $category->id) }}">{{ $category->name }}</a></li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ url('/products') }}">{{ __('messages.all_categories') }}</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    @if(!request()->is('admin*'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe me-1"></i> {{ app()->getLocale() == 'en' ? 'EN' : 'KH' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                                    <span class="flag-icon flag-icon-us me-2"></span> EN - English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'km' ? 'active' : '' }}" href="{{ route('language.switch', 'km') }}">
                                    <span class="flag-icon flag-icon-kh me-2"></span> KH - ខ្មែរ
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('messages.login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>{{ __('messages.register') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ url('/cart') }}">
                                <i class="bi bi-cart3 me-1"></i>{{ __('messages.my_cart') }}
                                @php
                                    $cartItemCount = 0;
                                    $cart = App\Models\Cart::where('user_id', Auth::id())->first();
                                    if ($cart) {
                                        $cartItemCount = $cart->items->count();
                                    }
                                @endphp
                                @if($cartItemCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartItemCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ url('/admin/dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('messages.admin_dashboard') }}</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ url('/user/orders') }}"><i class="bi bi-bag-check me-2"></i>{{ __('messages.my_orders') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/user/profile') }}"><i class="bi bi-person me-2"></i>{{ __('messages.my_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/cart') }}"><i class="bi bi-cart3 me-2"></i>{{ __('messages.my_cart') }}</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5 mt-5 flex-grow-1">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4"><i class="bi bi-shop me-2"></i>{{ config('app.name', 'Laravel') }}</h5>
                    <p class="small text-muted">Your one-stop shop for all your shopping needs. Quality products, competitive prices, and excellent customer service.</p>
                    <div class="mt-4">
                        <a href="#" class="text-white me-3 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3 fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-3 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-4">{{ __('messages.products') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/products') }}" class="text-decoration-none text-white-50">{{ __('messages.all_products') }}</a></li>
                        <li class="mb-2"><a href="{{ url('/products?featured=1') }}" class="text-decoration-none text-white-50">{{ __('messages.featured_products') }}</a></li>
                        <li class="mb-2"><a href="{{ url('/products?new=1') }}" class="text-decoration-none text-white-50">{{ __('messages.new_arrivals') }}</a></li>
                        <li class="mb-2"><a href="{{ url('/products?sale=1') }}" class="text-decoration-none text-white-50">{{ __('messages.sale_items') }}</a></li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-4">{{ __('messages.customer_service') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">{{ __('messages.contact_us') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">{{ __('messages.faqs') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">{{ __('messages.shipping_policy') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">{{ __('messages.returns_refunds') }}</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h5 class="mb-4">{{ __('messages.contact_info') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>123 Main Street, City, Country</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>(123) 456-7890</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>info@example.com</li>
                        <li class="mb-2"><i class="bi bi-clock me-2"></i>Mon-Fri: 9AM - 5PM</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 bg-secondary">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-white-50 mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('messages.all_rights_reserved') }}</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small text-white-50 mb-0">
                        <a href="#" class="text-white-50 text-decoration-none me-3">{{ __('messages.privacy_policy') }}</a>
                        <a href="#" class="text-white-50 text-decoration-none me-3">{{ __('messages.terms_of_service') }}</a>
                        <a href="#" class="text-white-50 text-decoration-none">{{ __('messages.sitemap') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
