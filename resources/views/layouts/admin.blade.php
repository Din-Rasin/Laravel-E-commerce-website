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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --sidebar-width: 250px;
            --topbar-height: 70px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }

        /* Sidebar */
        .admin-sidebar {
            min-height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1030; /* Higher z-index to ensure it's above other elements */
            position: fixed;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
            color: white;
        }

        .sidebar-brand-text {
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: white;
        }

        .admin-sidebar .nav-item {
            position: relative;
        }

        .admin-sidebar .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .admin-sidebar .nav-link:hover {
            color: #fff;
            border-left-color: rgba(255, 255, 255, 0.3);
        }

        .admin-sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            border-left-color: #fff;
            font-weight: 600;
        }

        .admin-sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 1.5rem;
            text-align: center;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0.5rem 0;
        }

        .sidebar-heading {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            padding: 0 1.5rem;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }

        /* Topbar */
        .admin-topbar {
            height: var(--topbar-height);
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            padding: 0 1.5rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-divider {
            width: 0;
            border-right: 1px solid #e3e6f0;
            height: calc(var(--topbar-height) - 2rem);
            margin: auto 1rem;
        }

        .admin-topbar .navbar-nav {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .admin-topbar .nav-item {
            position: relative;
            margin-left: 0.75rem;
        }

        .admin-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--topbar-height) + 1.5rem) 1.5rem 1.5rem;
            transition: all 0.3s;
        }

        /* Cards */
        .admin-card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            width: 100%;
            overflow: hidden;
        }

        .admin-card .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card .card-header h6 {
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary-color);
            margin: 0;
        }

        .admin-card .card-body {
            padding: 1.25rem;
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-left: 0.25rem solid;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .stat-card.primary {
            border-left-color: var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.info {
            border-left-color: var(--info-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.danger {
            border-left-color: var(--danger-color);
        }

        .stat-card .card-body {
            padding: 1.25rem;
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
            transition: opacity 0.2s ease-in-out;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.5;
        }

        .stat-card .stat-title {
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.25rem;
            letter-spacing: 0.05rem;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .stat-card .stat-value {
                font-size: 1.25rem;
            }

            .stat-card .stat-icon {
                font-size: 1.75rem;
            }

            .admin-card .card-header {
                padding: 0.75rem 1rem;
            }

            .admin-card .card-header h6 {
                font-size: 0.9rem;
            }

            .admin-card .card-body {
                padding: 1rem;
            }

            .admin-card .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Tables */
        .table-responsive {
            border-radius: 0.35rem;
            overflow: hidden;
            width: 100%;
        }

        .admin-table {
            margin-bottom: 0;
            font-size: 0.9rem;
            width: 100%;
            table-layout: auto;
        }

        .admin-table thead th {
            background-color: #f8f9fc;
            border-top: none;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: var(--secondary-color);
            padding: 0.75rem 0.5rem;
            white-space: nowrap;
        }

        .admin-table tbody td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
            border-color: #e3e6f0;
            word-break: break-word;
        }

        .admin-table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        /* Column widths */
        .admin-table th.col-id,
        .admin-table td.col-id {
            width: 5%;
            min-width: 50px;
        }

        .admin-table th.col-name,
        .admin-table td.col-name {
            width: 20%;
        }

        .admin-table th.col-email,
        .admin-table td.col-email {
            width: 30%;
        }

        .admin-table th.col-role,
        .admin-table td.col-role {
            width: 10%;
            text-align: center;
        }

        .admin-table th.col-date,
        .admin-table td.col-date {
            width: 15%;
        }

        .admin-table th.col-actions,
        .admin-table td.col-actions {
            width: 10%;
            text-align: center;
        }

        .admin-table th.col-status,
        .admin-table td.col-status {
            width: 15%;
            text-align: center;
        }

        .admin-table th.col-amount,
        .admin-table td.col-amount {
            width: 15%;
            text-align: right;
        }

        /* Buttons */
        .btn-icon-split {
            display: inline-flex;
            align-items: center;
        }

        .btn-icon-split .icon {
            background: rgba(0, 0, 0, 0.15);
            display: inline-block;
            padding: 0.375rem 0.75rem;
        }

        .btn-icon-split .text {
            display: inline-block;
            padding: 0.375rem 0.75rem;
        }

        /* Utilities */
        .bg-gradient-primary {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(180deg, #1cc88a 10%, #13855c 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(180deg, #36b9cc 10%, #258391 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(180deg, #f6c23e 10%, #dda20a 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(180deg, #e74a3b 10%, #be2617 100%);
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .text-lg {
            font-size: 1.2rem;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            display: none;
            width: 2.75rem;
            height: 2.75rem;
            text-align: center;
            color: #fff;
            background: rgba(78, 115, 223, 0.5);
            line-height: 2.75rem;
            z-index: 1030;
            transition: all 0.3s;
        }

        .scroll-to-top:hover {
            background: rgba(78, 115, 223, 0.8);
            color: #fff;
        }

        .scroll-to-top i {
            font-weight: 800;
        }

        /* Dropdown Animation */
        .animated--grow-in {
            animation-name: growIn;
            animation-duration: 0.2s;
            animation-timing-function: transform cubic-bezier(0.18, 1.25, 0.4, 1), opacity cubic-bezier(0, 1, 0.4, 1);
        }

        @keyframes growIn {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Icon Circles */
        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        /* Dropdown List */
        .dropdown-list {
            padding: 0;
            border: none;
            overflow: hidden;
            width: 20rem;
        }

        .dropdown-list .dropdown-header {
            background-color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.8rem;
            color: #fff;
        }

        .dropdown-list .dropdown-item {
            white-space: normal;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .dropdown-list .dropdown-item:active {
            background-color: #f8f9fc;
            color: #3a3b45;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 0;
                transform: translateX(-100%);
                z-index: 1040;
            }

            .admin-topbar, .admin-content {
                left: 0;
                width: 100%;
            }

            .admin-content {
                margin-left: 0;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .admin-sidebar.show {
                width: var(--sidebar-width);
                transform: translateX(0);
            }

            .admin-topbar.sidebar-open, .admin-content.sidebar-open {
                left: var(--sidebar-width);
            }

            .table-responsive {
                overflow-x: auto;
                margin: 0 -1rem;
                padding: 0 1rem;
                width: calc(100% + 2rem);
            }

            .admin-table {
                width: 100%;
                min-width: auto;
            }

            .admin-table thead th,
            .admin-table tbody td {
                padding: 0.5rem 0.35rem;
                font-size: 0.85rem;
            }

            .admin-table th.col-actions,
            .admin-table td.col-actions {
                width: 60px;
            }
        }

        /* Modal Styles */
        .modal-header {
            padding: 0.75rem 1rem;
        }

        .modal-header .btn-close {
            margin: -0.5rem -0.5rem -0.5rem auto;
        }

        .modal-title {
            font-weight: 700;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(78, 115, 223, 0.1);
        }

        .user-details .row {
            border-bottom: 1px solid #f8f9fc;
            padding-bottom: 0.5rem;
        }

        .user-details .row:last-child {
            border-bottom: none;
        }

        @media (max-width: 576px) {
            .admin-content {
                padding: calc(var(--topbar-height) + 1rem) 0.75rem 1rem;
            }

            .admin-topbar {
                padding: 0 0.75rem;
            }

            .card-header {
                padding: 0.75rem;
            }

            .card-body {
                padding: 1rem;
            }

            .admin-table {
                font-size: 0.8rem;
            }

            .admin-table thead th {
                font-size: 0.7rem;
                padding: 0.5rem 0.25rem;
            }

            .admin-table tbody td {
                padding: 0.5rem 0.25rem;
            }

            .btn-sm {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }

            .badge {
                font-size: 0.65rem;
                padding: 0.25rem 0.4rem;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <!-- Sidebar Brand -->
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="sidebar-brand-text">{{ config('app.name', 'Laravel') }}</div>
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Heading -->
            <div class="sidebar-heading">Core</div>

            <!-- Nav Items -->
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Sidebar Heading -->
                <div class="sidebar-heading">Store Management</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ url('/admin/products') }}">
                        <i class="bi bi-box"></i>
                        <span>Products</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ url('/admin/categories') }}">
                        <i class="bi bi-tags"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="{{ url('/admin/orders') }}">
                        <i class="bi bi-bag"></i>
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Sidebar Heading -->
                <div class="sidebar-heading">User Management</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Sidebar Heading -->
                <div class="sidebar-heading">Settings</div>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">
                        <i class="bi bi-house"></i>
                        <span>Visit Website</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="admin-topbar">
                    <!-- Sidebar Toggle (Topbar) -->
                    <div class="d-flex align-items-center">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
                            <i class="bi bi-list"></i>
                        </button>

                        <!-- Page Title -->
                        <h1 class="h5 mb-0 text-gray-800 d-none d-md-inline-block">
                            {{ config('app.name', 'Laravel') }} Admin
                        </h1>
                    </div>

                    <!-- Topbar Search Removed -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav">
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <!-- Counter - Alerts -->
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header bg-primary text-white">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="bi bi-cart text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2023</div>
                                        <span class="fw-bold">A new order has been placed!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-md-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 d-none d-lg-inline text-gray-600">{{ Auth::user()->name }}</span>
                                <i class="bi bi-person-circle fs-5"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear me-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="admin-content">
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
                <!-- End of Page Content -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Admin Dashboard Scripts -->
    <script>
        // Toggle the side navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            if (sidebarToggleTop) {
                sidebarToggleTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector('body').classList.toggle('sidebar-toggled');
                    document.querySelector('.admin-sidebar').classList.toggle('show');
                });
            }

            // Close any open menu accordions when window is resized
            window.addEventListener('resize', function() {
                const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
                if (vw < 768) {
                    document.querySelector('.admin-sidebar').classList.remove('show');
                    document.querySelector('body').classList.remove('sidebar-toggled');
                }
            });

            // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
            document.querySelector('.admin-sidebar').addEventListener('mousewheel', function(e) {
                if (this.scrollTop === 0 && e.deltaY < 0) {
                    e.preventDefault();
                } else if (this.scrollHeight === this.scrollTop + this.offsetHeight && e.deltaY > 0) {
                    e.preventDefault();
                }
            });

            // Scroll to top button
            const scrollToTop = document.querySelector('.scroll-to-top');
            if (scrollToTop) {
                // Scroll to top button appear
                window.addEventListener('scroll', function() {
                    const scrollDistance = window.pageYOffset;
                    if (scrollDistance > 100) {
                        scrollToTop.style.display = 'block';
                    } else {
                        scrollToTop.style.display = 'none';
                    }
                });

                // Smooth scrolling to top
                scrollToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
