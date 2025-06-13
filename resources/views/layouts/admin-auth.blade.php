<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }} Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        :root {
            /* Dark Slate Theme */
            --primary-color: #343a40;
            --primary-hover: #23272b;
            --secondary-color: #6c757d;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --success-color: #198754;
            --danger-color: #dc3545;
            --bg-color: #f0f2f5;
            --card-bg: #ffffff;
            --input-bg: #f0f5fa;
            --header-bg: #343a40;
            --text-color: #495057;
            --text-muted: #6c757d;
            --link-color: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

        .admin-auth-header {
            background-color: var(--dark-color);
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .admin-auth-footer {
            margin-top: auto;
            padding: 0.5rem 0;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .login-container {
            max-width: 400px;
            margin: 3rem auto;
        }

        .login-card {
            border: none;
            border-radius: 0;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background-color: #ffffff;
        }

        .login-header {
            background-color: var(--header-bg);
            color: white;
            padding: 0.75rem;
            text-align: center;
            position: relative;
        }

        .login-header h4 {
            font-weight: 600;
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        .login-body {
            padding: 1.5rem;
        }

        .login-form .form-control {
            border-radius: 0;
            padding: 0.6rem 0.75rem;
            border: 1px solid #dee2e6;
            background-color: #f0f5fa;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .login-form .form-control:focus {
            border-color: #adb5bd;
            box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .login-form label {
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .login-btn {
            width: 100%;
            padding: 0.6rem;
            font-weight: 500;
            border-radius: 0;
            background-color: #343a40;
            border: none;
            transition: all 0.2s;
            color: white;
            font-size: 0.9rem;
        }

        .login-btn:hover {
            background-color: var(--primary-hover);
            opacity: 0.95;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--link-color);
            text-decoration: none;
            font-weight: 400;
            transition: all 0.2s;
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border: 1px solid #dee2e6;
            border-radius: 0;
        }

        .back-link:hover {
            background-color: #f8f9fa;
            color: var(--text-color);
        }

        .invalid-feedback {
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--danger-color);
        }

        .form-check-input:checked {
            background-color: #343a40;
            border-color: #343a40;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .logo-text {
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .shield-icon {
            font-size: 1.1rem;
            margin-right: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .input-group-text {
            background-color: var(--input-bg);
            border-color: #dee2e6;
            color: var(--text-muted);
        }

        .login-instructions {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 1.5rem;
        }
    </style>

    @yield('styles')
</head>
<body>
    <header class="admin-auth-header">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <a href="{{ url('/') }}" class="text-white text-decoration-none">
                    <h5 class="mb-0 logo-text"><i class="bi bi-shop me-1"></i>{{ config('app.name', 'Laravel') }}</h5>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="admin-auth-footer">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
