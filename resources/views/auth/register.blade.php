@extends('layouts.app')

@section('title', __('messages.register'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-center fw-bold">
                        <i class="bi bi-person-plus me-2"></i>{{ __('messages.create_account') }}
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <p class="text-muted">{{ __('messages.join_community') }}</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Your Name">
                            <label for="name">{{ __('messages.full_name') }}</label>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">
                            <label for="email">{{ __('messages.email_address') }}</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                            <label for="password">{{ __('messages.password') }}</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text small mt-2">
                                <i class="bi bi-info-circle me-1"></i>Password must be at least 8 characters long.
                            </div>
                        </div>

                        <div class="form-floating mb-4">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            <label for="password-confirm">{{ __('messages.confirm_password') }}</label>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>{{ __('messages.create_account') }}
                            </button>
                        </div>

                        <div class="text-center mb-4">
                            <p class="text-muted mb-3">{{ __('messages.or_register_with') }}</p>
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
                            <p class="mb-0">{{ __('messages.already_have_account') }} <a href="{{ route('login') }}" class="text-decoration-none fw-bold">{{ __('messages.login') }}</a></p>
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
