@extends('layouts.admin-auth')

@section('title', 'Admin Login')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h4 class="d-flex align-items-center justify-content-center">
                <i class="bi bi-shield-lock shield-icon"></i>
                <span>Admin Login</span>
            </h4>
        </div>

        <div class="login-body">
            <div class="login-instructions text-center mb-4">
                <p>Please enter your credentials to access the admin dashboard.</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="login-form">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="login-btn">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ url('/') }}" class="back-link">
                        <i class="bi bi-arrow-left me-1"></i>Back to Website
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
