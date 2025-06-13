@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="bi bi-download me-1"></i> Generate Report
    </a>
</div>

<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card admin-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Welcome Back!
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Hello, {{ Auth::user()->name }}!</div>
                        <p class="mt-2 mb-0">This is your admin dashboard. You can manage your e-commerce website from here.</p>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-circle fa-2x text-gray-300" style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row mb-4">
    <!-- Total Products Card -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col-8">
                        <div class="stat-title">
                            TOTAL PRODUCTS
                        </div>
                        <div class="stat-value">{{ $totalProducts }}</div>
                        <div class="mt-2">
                            <span class="text-success me-1"><i class="bi bi-arrow-up"></i> 12%</span>
                            <span class="text-xs">Since last month</span>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-box stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="{{ url('/admin/products') }}" class="text-primary text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col-8">
                        <div class="stat-title">
                            TOTAL ORDERS
                        </div>
                        <div class="stat-value">{{ $totalOrders }}</div>
                        <div class="mt-2">
                            <span class="text-success me-1"><i class="bi bi-arrow-up"></i> 8%</span>
                            <span class="text-xs">Since last month</span>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-bag stat-icon text-success"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="{{ url('/admin/orders') }}" class="text-success text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Revenue Card -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col-8">
                        <div class="stat-title">
                            TOTAL REVENUE
                        </div>
                        <div class="stat-value">${{ number_format($totalRevenue ?? 0, 2) }}</div>
                        <div class="mt-2">
                            <span class="text-success me-1"><i class="bi bi-arrow-up"></i> 15%</span>
                            <span class="text-xs">Since last month</span>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-currency-dollar stat-icon text-info"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="{{ url('/admin/orders') }}" class="text-info text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col-8">
                        <div class="stat-title">
                            TOTAL USERS
                        </div>
                        <div class="stat-value">{{ $totalUsers }}</div>
                        <div class="mt-2">
                            <span class="text-success me-1"><i class="bi bi-arrow-up"></i> 5%</span>
                            <span class="text-xs">Since last month</span>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-people stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-2">
                <a href="{{ url('/admin/users') }}" class="text-warning text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row mb-4">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7 col-md-12 mb-4">
        <div class="card admin-card shadow h-100">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Time Range:</div>
                        <a class="dropdown-item active" href="#">Last 30 Days</a>
                        <a class="dropdown-item" href="#">Last Quarter</a>
                        <a class="dropdown-item" href="#">Last Year</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Export Data</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area" style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
        <div class="card admin-card shadow h-100">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink2">
                        <div class="dropdown-header">View Options:</div>
                        <a class="dropdown-item active" href="#">By Category</a>
                        <a class="dropdown-item" href="#">By Product</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Export Data</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-2 pb-2" style="height: 250px;">
                    <canvas id="revenueSources"></canvas>
                </div>
                <div class="mt-3 text-center small">
                    <span class="me-2">
                        <i class="bi bi-circle-fill text-primary"></i> Electronics
                    </span>
                    <span class="me-2">
                        <i class="bi bi-circle-fill text-success"></i> Clothing
                    </span>
                    <span class="me-2">
                        <i class="bi bi-circle-fill text-info"></i> Books
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-clock-history me-2"></i>Recent Orders</h6>
                <a href="{{ url('/admin/orders') }}" class="btn btn-sm btn-primary">
                    <span class="d-none d-sm-inline">View All</span>
                    <i class="bi bi-arrow-right d-inline d-sm-none"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->isEmpty())
                    <div class="text-center py-4">
                        <i class="bi bi-bag-x" style="font-size: 3rem; opacity: 0.2;"></i>
                        <p class="text-muted mt-2">No orders yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table admin-table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="col-id">Order #</th>
                                    <th class="col-name">Customer</th>
                                    <th class="col-amount">Amount</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-date">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="col-id">
                                            <button type="button" class="btn btn-link text-primary fw-bold p-0 view-order-btn"
                                                data-order-id="{{ $order->id }}"
                                                data-order-date="{{ $order->created_at->format('M d, Y') }}"
                                                data-order-status="{{ $order->status }}"
                                                data-order-total="{{ number_format($order->total_amount, 2) }}"
                                                data-customer-name="{{ $order->user->name }}"
                                                data-customer-email="{{ $order->user->email }}">
                                                {{ $order->id }}
                                            </button>
                                        </td>
                                        <td class="col-name">{{ $order->user->name }}</td>
                                        <td class="col-amount">${{ number_format($order->total_amount, 2) }}</td>
                                        <td class="col-status">
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td class="col-date">{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-people me-2"></i>Recent Users</h6>
                <a href="{{ url('/admin/users') }}" class="btn btn-sm btn-primary">
                    <span class="d-none d-sm-inline">View All</span>
                    <i class="bi bi-arrow-right d-inline d-sm-none"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentUsers->isEmpty())
                    <div class="text-center py-4">
                        <i class="bi bi-person-x" style="font-size: 3rem; opacity: 0.2;"></i>
                        <p class="text-muted mt-2">No users yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table admin-table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="col-name">Name</th>
                                    <th class="col-email">Email</th>
                                    <th class="col-role">Role</th>
                                    <th class="col-date">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                    <tr>
                                        <td class="col-name">{{ $user->name }}</td>
                                        <td class="col-email">{{ $user->email }}</td>
                                        <td class="col-role">
                                            @if($user->isAdmin())
                                                <span class="badge bg-primary">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">User</span>
                                            @endif
                                        </td>
                                        <td class="col-date">{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Include User Modals -->
@include('admin.components.user-modal')

<!-- Include Order Modals -->
@include('admin.components.order-modal')

@section('scripts')
<script>
    // Area Chart - Revenue Overview
    document.addEventListener('DOMContentLoaded', function() {
        // Function to handle chart resizing
        function resizeCharts() {
            if (window.myLineChart) {
                window.myLineChart.resize();
            }
            if (window.myPieChart) {
                window.myPieChart.resize();
            }
        }

        // Add resize event listener
        window.addEventListener('resize', function() {
            // Debounce resize event
            clearTimeout(window.resizeTimer);
            window.resizeTimer = setTimeout(resizeCharts, 250);
        });

        // Area Chart Example
        var ctx = document.getElementById("revenueChart");
        if (ctx) {
            ctx = ctx.getContext('2d');
            window.myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Revenue",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            @foreach($monthlyRevenue as $revenue)
                                {{ $revenue }},
                            @endforeach
                        ],
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7,
                                autoSkip: true,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': $' + tooltipItem.yLabel;
                            }
                        }
                    }
                }
            });
        }

        // Pie Chart Example
        var ctx2 = document.getElementById("revenueSources");
        if (ctx2) {
            ctx2 = ctx2.getContext('2d');
            window.myPieChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ["Electronics", "Clothing", "Books"],
                    datasets: [{
                        data: [55, 30, 15],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }

        // Toggle sidebar on mobile
        const sidebarToggle = document.getElementById('sidebarToggleTop');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.admin-sidebar').classList.toggle('show');
                document.querySelector('.admin-topbar').classList.toggle('sidebar-open');
                document.querySelector('.admin-content').classList.toggle('sidebar-open');
            });
        }

        // User Modal Functionality
        // View User Modal
        document.querySelectorAll('.view-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                const userEmail = this.getAttribute('data-user-email');
                const userRole = this.getAttribute('data-user-role');
                const userJoined = this.getAttribute('data-user-joined');

                // Set modal content
                document.querySelector('#userViewModal .user-name').textContent = userName;
                document.querySelector('#userViewModal .user-email').textContent = userEmail;
                document.querySelector('#userViewModal .user-role .badge').textContent = userRole;
                document.querySelector('#userViewModal .user-id').textContent = userId;
                document.querySelector('#userViewModal .user-joined').textContent = userJoined;

                // Set edit button user ID
                document.querySelector('#userViewModal .edit-user-btn').setAttribute('data-user-id', userId);

                // Show the modal
                const userViewModal = new bootstrap.Modal(document.getElementById('userViewModal'));
                userViewModal.show();
            });
        });

        // Edit User Modal
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                const userEmail = this.getAttribute('data-user-email');
                const userRole = this.getAttribute('data-user-role');

                // Set form values
                document.querySelector('#edit-name').value = userName;
                document.querySelector('#edit-email').value = userEmail;
                document.querySelector('#edit-role').value = userRole;

                // Set form action
                document.querySelector('#editUserForm').action = `/admin/users/${userId}`;

                // Close view modal if open
                const userViewModal = bootstrap.Modal.getInstance(document.getElementById('userViewModal'));
                if (userViewModal) {
                    userViewModal.hide();
                }

                // Show edit modal
                const userEditModal = new bootstrap.Modal(document.getElementById('userEditModal'));
                userEditModal.show();
            });
        });

        // Delete User Modal
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');

                // Set user name in confirmation message
                document.querySelector('.user-delete-name').textContent = userName;

                // Set form action
                document.querySelector('#deleteUserForm').action = `/admin/users/${userId}`;

                // Show delete modal
                const userDeleteModal = new bootstrap.Modal(document.getElementById('userDeleteModal'));
                userDeleteModal.show();
            });
        });

        // Handle edit button in view modal
        document.querySelector('#userViewModal .edit-user-btn').addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');

            // Find and click the corresponding edit button in the table
            document.querySelector(`.edit-user-btn[data-user-id="${userId}"]`).click();
        });

        // Order Modal Functionality
        // View Order Modal
        document.querySelectorAll('.view-order-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const orderDate = this.getAttribute('data-order-date');
                const orderStatus = this.getAttribute('data-order-status');
                const orderTotal = this.getAttribute('data-order-total');
                const customerName = this.getAttribute('data-customer-name');
                const customerEmail = this.getAttribute('data-customer-email');

                // Set modal content
                document.querySelector('#orderViewModal .order-id').textContent = orderId;
                document.querySelector('#orderViewModal .order-date').textContent = orderDate;
                document.querySelector('#orderViewModal .order-total').textContent = '$' + orderTotal;
                document.querySelector('#orderViewModal .order-total-footer').textContent = '$' + orderTotal;
                document.querySelector('#orderViewModal .customer-name').textContent = customerName;
                document.querySelector('#orderViewModal .customer-email').textContent = customerEmail;

                // Set status with appropriate badge
                let statusBadge = '';
                if (orderStatus === 'pending') {
                    statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                } else if (orderStatus === 'processing') {
                    statusBadge = '<span class="badge bg-info">Processing</span>';
                } else if (orderStatus === 'completed') {
                    statusBadge = '<span class="badge bg-success">Completed</span>';
                } else if (orderStatus === 'cancelled') {
                    statusBadge = '<span class="badge bg-danger">Cancelled</span>';
                }
                document.querySelector('#orderViewModal .order-status').innerHTML = statusBadge;

                // Set edit button order ID
                document.querySelector('#orderViewModal .edit-order-btn').setAttribute('data-order-id', orderId);

                // For demonstration, we'll add some sample order items
                // In a real application, you would fetch these from the server
                const orderItems = [
                    { name: 'Sample Product 1', price: '29.99', quantity: 1, subtotal: '29.99' },
                    { name: 'Sample Product 2', price: '49.99', quantity: 2, subtotal: '99.98' }
                ];

                // Clear existing items
                document.getElementById('orderItemsTable').innerHTML = '';

                // Add order items to the table
                orderItems.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>$${item.price}</td>
                        <td>${item.quantity}</td>
                        <td>$${item.subtotal}</td>
                    `;
                    document.getElementById('orderItemsTable').appendChild(row);
                });

                // Show the modal
                const orderViewModal = new bootstrap.Modal(document.getElementById('orderViewModal'));
                orderViewModal.show();
            });
        });

        // Edit Order Status
        document.querySelector('#orderViewModal .edit-order-btn').addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');

            // Set form action
            document.querySelector('#updateOrderStatusForm').action = `/admin/orders/${orderId}`;

            // Close view modal
            const orderViewModal = bootstrap.Modal.getInstance(document.getElementById('orderViewModal'));
            orderViewModal.hide();

            // Show status update modal
            const orderStatusModal = new bootstrap.Modal(document.getElementById('orderStatusModal'));
            orderStatusModal.show();
        });
    });
</script>
@endsection
