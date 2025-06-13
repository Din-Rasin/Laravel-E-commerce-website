@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-box me-2"></i>Product Details</h1>
    <div>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary shadow-sm me-2">
            <i class="bi bi-pencil me-1"></i> Edit Product
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>
</div>

<!-- Product Information Row -->
<div class="row mb-4">
    <!-- Product Image Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product Image</h6>
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded product-image">
                @else
                    <div class="no-image-placeholder">
                        <i class="bi bi-image text-secondary"></i>
                        <p class="text-muted mt-2">No image available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Details Card -->
    <div class="col-xl-8 col-md-6 mb-4">
        <div class="card admin-card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
            </div>
            <div class="card-body">
                <div class="product-title">
                    <h4 class="font-weight-bold mb-1">{{ $product->name }}</h4>
                    <div class="mb-3">
                        <span class="badge bg-secondary">{{ $product->category ? $product->category->name : 'Uncategorized' }}</span>
                        @if($product->featured)
                            <span class="badge bg-primary">Featured</span>
                        @endif
                        @if($product->active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-currency-dollar me-2"></i>Price:</span>
                            <span class="detail-value">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-box-seam me-2"></i>Quantity in Stock:</span>
                            <span class="detail-value">{{ $product->quantity }}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-text-paragraph me-2"></i>Description:</span>
                    <div class="detail-value">
                        {{ $product->description }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-calendar me-2"></i>Created:</span>
                            <span class="detail-value">{{ $product->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-clock me-2"></i>Last Updated:</span>
                            <span class="detail-value">{{ $product->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Product
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i> Delete Product
                    </button>
                </div>
            </div>
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
                Are you sure you want to delete the product <strong>{{ $product->name }}</strong>?
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
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
    .product-image {
        max-height: 300px;
        width: auto;
        margin: 0 auto;
    }

    .no-image-placeholder {
        height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fc;
        border-radius: 0.35rem;
    }

    .no-image-placeholder i {
        font-size: 4rem;
        opacity: 0.2;
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
</style>
@endsection
