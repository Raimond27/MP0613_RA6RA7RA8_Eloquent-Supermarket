@extends('layout')

@section('content')
    <div class="container mt-5 mb-3">
        @if (Auth::check() && Auth::user()->hasAccess('employee_access'))
            <div class="border p-4 bg-light rounded mb-4">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="productName" class="form-control" required>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea name="description" id="productDescription" rows="3" class="form-control"></textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message ?? '' }}</div>
                        @enderror
                    </div>

                    <!-- Category Dropdown -->
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="productCategory" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger mt-1">{{ $message ?? '' }}</div>
                        @enderror
                    </div>

                    <!-- Base Price -->
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Base Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" id="productPrice" class="form-control" required>
                        @error('price')
                            <div class="text-danger mt-1">{{ $message ?? '' }}</div>
                        @enderror
                    </div>

                    <!-- Images Upload -->
                    <div class="mb-3">
                        <label for="productImages" class="form-label">Product Images <span
                                class="text-danger">*</span></label>
                        <input type="file" name="images[]" id="productImages" class="form-control" multiple required>
                        <small class="text-muted">At least one image is required. You can upload more than one.</small>
                        @error('images')
                            <div class="text-danger mt-1">{{ $message ?? '' }}</div>
                        @enderror
                        @if ($errors->has('images.*'))
                            @foreach ($errors->get('images.*') as $error)
                                <div class="text-danger mt-1">{{ $error[0] }}</div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary">Create Product</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="container mb-4">
            <form method="GET" action="{{ route('products.index') }}"
                class="row row-cols-auto g-2 align-items-center justify-content-center">

                <!-- Name Filter -->
                <div class="col">
                    <input type="text" class="form-control" name="name" placeholder="Product name"
                        value="{{ request('name') }}">
                </div>

                <!-- Price Range -->
                <div class="col">
                    <input type="number" class="form-control" name="min_price" placeholder="Min Price"
                        value="{{ request('min_price') }}">
                </div>

                <div class="col">
                    <input type="number" class="form-control" name="max_price" placeholder="Max Price"
                        value="{{ request('max_price') }}">
                </div>

                <!-- Category Selector -->
                <div class="col">
                    <select class="form-select" name="category_id">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div class="col">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                </div>
                @if (Auth::check() && Auth::user()->hasAccess('employee_access'))
                    <div class="col">
                        <a href="{{ route('products.export') }}" class="btn btn-success">
                            Export XLS
                        </a>
                    </div>
                @endif
            </form>
        </div>


        <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
            @if ($products->isEmpty())
                <div class="w-100 d-flex justify-content-center">
                    <p class="text-center text-muted">No products found for this search.</p>
                </div>
            @else
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100 position-relative">
                            <div class="product-image-container"
                                data-images="{{ json_encode($product->images->pluck('image_path')->toArray()) }}">

                                <!-- Main Image -->
                                <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                    class="card-img-top product-image" alt="Product Image">

                                <!-- Carousel Controls (Show only if multiple images exist) -->
                                @if ($product->images->count() > 1)
                                    <button class="carousel-control left-arrow">&#9664;</button>
                                    <button class="carousel-control right-arrow">&#9654;</button>
                                @endif
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold d-flex justify-content-between align-items-center">
                                    {{ $product->name }}
                                    <span class="badge bg-secondary" data-bs-toggle="tooltip"
                                        title="{{ $product->category->description }}">
                                        {{ $product->category->name }}
                                    </span>
                                </h5>
                                <p class="card-text">{{ $product->description }}</p>
                            </div>

                            <div class="card-footer d-flex justify-content-end align-items-center">
                                <span class="fw-bold text-primary">${{ number_format($product->final_price, 2) }}</span>
                                @if (Auth::check() && Auth::user()->hasAccess('employee_access'))
                                    <a href="{{ route('products.exportPdf', $product->id) }}"
                                        class="btn btn-sm btn-outline-secondary ms-2">
                                        PDF
                                    </a>

                                    <button type="button" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $product->id }}">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $product->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    @include('components.edit-product-modal', [
                                        'product' => $product,
                                    ])

                                    @include('components.confirmation-modal', [
                                        'modalId' => "deleteModal{$product->id}",
                                        'title' => 'Confirm Delete',
                                        'message' => 'Are you sure you want to delete this product?',
                                        'action' => route('products.destroy', $product->id),
                                        'method' => 'DELETE',
                                        'confirmButton' => 'Delete',
                                    ])
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination Links -->
        <div class="mt-4 d-flex flex-column align-items-center">
            {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

    <style>
        .product-image-container {
            position: relative;
            overflow: hidden;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            font-size: 24px;
            padding: 10px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .left-arrow {
            left: 10px;
        }

        .right-arrow {
            right: 10px;
        }

        .product-image-container:hover .carousel-control {
            opacity: 1;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Function to update the displayed product image based on navigation direction
                function updateImage(container, direction) {
                    // Retrieve and parse the JSON-encoded array of image paths from the container's data attribute
                    let images = JSON.parse(container.dataset.images);
                    // Select the image element within the container
                    let imgElement = container.querySelector('.product-image');
                    // Determine the current image index by removing the base URL prefix from the src
                    let currentIndex = images.indexOf(imgElement.src.replace(window.location.origin + '/storage/', ''));

                    // Calculate the new index either by moving to the next image or the previous one, wrapping around using modulo arithmetic
                    let newIndex = direction === 'next' ?
                        (currentIndex + 1) % images.length :
                        (currentIndex - 1 + images.length) % images.length;

                    // Update the image source to the new image path with the full storage URL prefix
                    imgElement.src = window.location.origin + "/storage/" + images[newIndex];
                }

                // Find all containers that hold product images
                document.querySelectorAll('.product-image-container').forEach(container => {
                    // When the mouse hovers over the container, make the carousel controls visible
                    container.addEventListener('mouseover', () => {
                        container.querySelectorAll('.carousel-control').forEach(btn => btn.style
                            .opacity = "1");
                    });

                    // When the mouse leaves the container, hide the carousel controls
                    container.addEventListener('mouseleave', () => {
                        container.querySelectorAll('.carousel-control').forEach(btn => btn.style
                            .opacity = "0");
                    });

                    // Attach click event to the left arrow, invoking updateImage for the "prev" direction
                    container.querySelector('.left-arrow')?.addEventListener('click', () => updateImage(
                        container, 'prev'));
                    // Attach click event to the right arrow, invoking updateImage for the "next" direction
                    container.querySelector('.right-arrow')?.addEventListener('click', () => updateImage(
                        container, 'next'));
                });
            });
        </script>
    @endpush
@endsection
