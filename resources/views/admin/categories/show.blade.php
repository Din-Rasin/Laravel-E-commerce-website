@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-tag me-2"></i>Category Details</h1>
    <div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary shadow-sm me-2">
            <i class="bi bi-pencil me-1"></i> Edit Category
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Categories
        </a>
    </div>
</div>

<!-- Category Information Row -->
<div class="row mb-4">
    <!-- Category Details Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="category-icon mb-3">
                        <i class="bi bi-tag"></i>
                    </div>
                    <h4 class="font-weight-bold">{{ $category->name }}</h4>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-text-paragraph me-2"></i>Description:</span>
                    <div class="detail-value">
                        {{ $category->description ?: 'No description available.' }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-box-seam me-2"></i>Total Products:</span>
                    <span class="detail-value">{{ $products->total() }}</span>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-calendar me-2"></i>Created:</span>
                            <span class="detail-value">{{ $category->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-clock me-2"></i>Last Updated:</span>
                            <span class="detail-value">{{ $category->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Category
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i> Delete Category
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Statistics Card -->
    <div class="col-xl-8 col-md-6 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Category Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="stat-title">TOTAL PRODUCTS</div>
                                        <div class="stat-value">{{ $products->total() }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-box stat-icon text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="stat-title">ACTIVE PRODUCTS</div>
                                        <div class="stat-value">{{ $products->where('active', true)->count() }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-check-circle stat-icon text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="stat-title">FEATURED PRODUCTS</div>
                                        <div class="stat-value">{{ $products->where('featured', true)->count() }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-star stat-icon text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="chart-area mt-4">
                    <canvas id="categoryProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products in Category -->
<div class="card admin-card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-box me-2"></i>Products in Category</h6>
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add New Product
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" width="50" height="50" class="img-thumbnail">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                @if($product->active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                                @if($product->featured)
                                    <span class="badge bg-primary">Featured</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-center py-4">
                                    <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <p class="text-muted mt-2">No products found in this category.</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the category <strong>{{ $category->name }}</strong>?
                @if($products->total() > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        This category has {{ $products->total() }} products. Deleting it may affect these products.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-icon {
        width: 80px;
        height: 80px;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }
    
    .category-icon i {
        color: white;
        font-size: 36px;
    }
    
    .detail-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .detail-label {
        font-weight: 600;
        color: #4e73df;
        display: block;
        margin-bottom: 5px;
    }
    
    .detail-value {
        color: #5a5c69;
    }
    
    .chart-area {
        height: 300px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pie Chart for Category Products
        var ctx = document.getElementById("categoryProductsChart");
        if (ctx) {
            ctx = ctx.getContext('2d');
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Active", "Inactive", "Featured"],
                    datasets: [{
                        data: [
                            {{ $products->where('active', true)->count() }}, 
                            {{ $products->where('active', false)->count() }}, 
                            {{ $products->where('featured', true)->count() }}
                        ],
                        backgroundColor: ['#1cc88a', '#e74a3b', '#4e73df'],
                        hoverBackgroundColor: ['#17a673', '#be2617', '#2e59d9'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
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
                        display: true,
                        position: 'bottom'
                    },
                    cutoutPercentage: 70,
                },
            });
        }
    });
</script>
@endsection
