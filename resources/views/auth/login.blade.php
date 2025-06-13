@extends('layouts.app')

@section('title', __('messages.login'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-center fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('messages.login_to_account') }}
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <p class="text-muted">{{ __('messages.welcome_back') }}</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            <label for="email">{{ __('messages.email_address') }}</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            <label for="password">{{ __('messages.password') }}</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('messages.remember_me') }}
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none small">{{ __('messages.forgot_password') }}</a>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('messages.login') }}
                            </button>
                        </div>

                        <div class="text-center mb-4">
                            <p class="text-muted mb-3">{{ __('messages.or_login_with') }}</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('social.login', 'google') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-google me-2"></i>Google
                                </a>
                                <a href="{{ route('social.login', 'facebook') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-facebook me-2"></i>Facebook
                                </a>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">{{ __('messages.dont_have_account') }} <a href="{{ route('register') }}" class="text-decoration-none fw-bold">{{ __('messages.register') }}</a></p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.all_rights_reserved') }}
                </p>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #3a86ff;
    }

    .form-control:focus {
        border-color: #3a86ff;
        box-shadow: 0 0 0 0.25rem rgba(58, 134, 255, 0.25);
    }
</style>
@endsection

@endsection
