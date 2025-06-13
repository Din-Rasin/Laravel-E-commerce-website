@extends('layouts.admin')

@section('title', 'Products')

@section('content')
@if(session('openAddProductModal') || $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
        addProductModal.show();
    });
</script>
@endif

<!-- Edit modal is now handled in the scripts section -->
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-box me-2"></i>Products</h1>
        @if(isset($lastProductId))
            <div class="mt-2">
                <span class="badge bg-primary fs-6">Last Product ID: {{ $lastProductId }}</span>
            </div>
        @endif
    </div>
    <div class="d-flex align-items-center">
        <form action="{{ route('admin.products.index') }}" method="GET" class="me-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search products..." name="search" value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus"></i> Add New Product
        </button>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> There were some problems with your input.
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif



<!-- Content Row -->
<div class="card admin-card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
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
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="50" height="50" class="img-thumbnail" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.jpg') }}';">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
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
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewProductModal{{ $product->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary edit-product-btn" data-product-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- View Product Modal -->
                                <div class="modal fade" id="viewProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="viewProductModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewProductModalLabel{{ $product->id }}"><i class="bi bi-eye me-2"></i>Product Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-5 text-center mb-4">
                                                        @if($product->image)
                                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded product-image mb-3" style="max-height: 250px;" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.jpg') }}';">
                                                        @else
                                                            <div class="no-image-placeholder mb-3" style="height: 250px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fc; border-radius: 0.35rem;">
                                                                <i class="bi bi-image text-secondary" style="font-size: 4rem; opacity: 0.2;"></i>
                                                            </div>
                                                        @endif

                                                        <div class="product-badges mb-3">
                                                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
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

                                                    <div class="col-md-7">
                                                        <h4 class="product-title mb-3">{{ $product->name }}</h4>

                                                        <div class="product-details">
                                                            <div class="detail-item mb-3">
                                                                <span class="detail-label"><i class="bi bi-currency-dollar me-2"></i>Price:</span>
                                                                <span class="detail-value">${{ number_format($product->price, 2) }}</span>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <span class="detail-label"><i class="bi bi-box-seam me-2"></i>Quantity in Stock:</span>
                                                                <span class="detail-value">{{ $product->quantity }}</span>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <span class="detail-label"><i class="bi bi-tag me-2"></i>Category:</span>
                                                                <span class="detail-value">{{ $product->category->name }}</span>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <span class="detail-label"><i class="bi bi-calendar me-2"></i>Created:</span>
                                                                <span class="detail-value">{{ $product->created_at->format('M d, Y') }}</span>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <span class="detail-label"><i class="bi bi-clock me-2"></i>Last Updated:</span>
                                                                <span class="detail-value">{{ $product->updated_at->format('M d, Y') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <div class="detail-item">
                                                            <span class="detail-label"><i class="bi bi-text-paragraph me-2"></i>Description:</span>
                                                            <div class="detail-value mt-2">
                                                                {{ $product->description }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                                    <i class="bi bi-pencil me-1"></i> Edit Product
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Product Modal -->
                                <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}"><i class="bi bi-pencil me-2"></i>Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="name{{ $product->id }}" class="form-label">Product Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name{{ $product->id }}" name="name" value="{{ old('name', $product->name) }}" required>
                                                            @error('name')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="category_id{{ $product->id }}" class="form-label">Category <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id{{ $product->id }}" name="category_id" required>
                                                                <option value="">Select Category</option>
                                                                @foreach(\App\Models\Category::all() as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('category_id')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="price{{ $product->id }}" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price{{ $product->id }}" name="price" value="{{ old('price', $product->price) }}" required>
                                                            @error('price')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="quantity{{ $product->id }}" class="form-label">Quantity in Stock <span class="text-danger">*</span></label>
                                                            <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity{{ $product->id }}" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                                                            @error('quantity')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="description{{ $product->id }}" class="form-label">Description <span class="text-danger">*</span></label>
                                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description{{ $product->id }}" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="image{{ $product->id }}" class="form-label">Product Image</label>
                                                        @if($product->image)
                                                            <div class="mb-2">
                                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 100px;" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.jpg') }}';">
                                                                <div class="form-text">Current image. Upload a new one to replace it.</div>
                                                            </div>
                                                        @endif
                                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image{{ $product->id }}" name="image">
                                                        <div class="form-text">Upload a product image (JPEG, PNG, JPG, GIF). Max size: 2MB.</div>
                                                        @error('image')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="featured{{ $product->id }}" name="featured" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="featured{{ $product->id }}">
                                                                    Featured Product
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="active{{ $product->id }}" name="active" {{ old('active', $product->active) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="active{{ $product->id }}">
                                                                    Active (Visible on the store)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-save me-1"></i> Update Product
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to move the product <strong>{{ $product->name }}</strong> to trash?</p>
                                                <p class="text-muted small">Note: The product will be moved to trash and can be restored later. It will not be visible in the store but will remain in the database.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Move to Trash</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-center py-4">
                                    <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.2;"></i>
                                    <p class="text-muted mt-2">No products found.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                        <i class="bi bi-plus"></i> Add New Product
                                    </button>
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

<!-- Trashed Products Section -->
@if(isset($trashedProducts) && $trashedProducts->count() > 0)
<div class="card admin-card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-danger"><i class="bi bi-trash me-2"></i>Trashed Products</h6>
        <span class="badge bg-danger">{{ $trashedProducts->count() }} items</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashedProducts as $product)
                        <tr class="table-danger bg-opacity-10">
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="50" height="50" class="img-thumbnail" onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.jpg') }}';">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->deleted_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.restore', $product->id) }}" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to restore this product?')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#forceDeleteModal{{ $product->id }}">
                                        <i class="bi bi-trash"></i> Delete Permanently
                                    </button>
                                </div>

                                <!-- Force Delete Modal -->
                                <div class="modal fade" id="forceDeleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="forceDeleteModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="forceDeleteModalLabel{{ $product->id }}">Confirm Permanent Deletion</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                    <strong>Warning!</strong> This action cannot be undone.
                                                </div>
                                                <p>Are you sure you want to <strong>permanently delete</strong> the product <strong>{{ $product->name }}</strong>?</p>
                                                <p>This will remove the product completely from the database and any associated images will be deleted.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel"><i class="bi bi-plus-circle me-2"></i>Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Quantity in Stock <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        <div class="form-text">Upload a product image (JPEG, PNG, JPG, GIF). Max size: 2MB.</div>
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    Featured Product
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" name="active" {{ old('active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    Active (Visible on the store)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .detail-label {
        font-weight: 600;
        color: #4e73df;
        display: block;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #5a5c69;
    }

    .product-title {
        color: #2e59d9;
        font-weight: 700;
    }

    .product-badges {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .fs-6 {
        font-size: 0.9rem !important;
    }

    .badge.bg-primary {
        padding: 0.5rem 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all edit buttons
        document.querySelectorAll('.edit-product-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const modalId = 'editProductModal' + productId;
                console.log('Edit button clicked for product ID:', productId, 'Modal ID:', modalId);

                // Ensure the modal exists
                const modal = document.getElementById(modalId);
                if (modal) {
                    // Use Bootstrap's Modal API to show the modal
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                } else {
                    console.error('Modal not found:', modalId);
                }
            });
        });

        // Check if we need to open an edit modal from server-side
        @if(session('openEditProductModal'))
            const productId = {{ session('openEditProductModal') }};
            const modalId = 'editProductModal' + productId;
            console.log('Opening edit modal from server side:', modalId);

            // Ensure the modal exists
            const modal = document.getElementById(modalId);
            if (modal) {
                // Use Bootstrap's Modal API to show the modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            } else {
                console.error('Modal not found:', modalId);
            }
        @endif
    });
</script>
@endsection
