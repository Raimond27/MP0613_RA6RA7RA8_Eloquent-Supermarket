@extends('layout')

@section('content')
    <div class="container mt-5">
        <!-- Page Title -->
        <h3 class="text-center">Category</h3>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Category Creation Form -->
                <div class="border p-4 bg-light rounded">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="row">
                            <!-- Category Name Input -->
                            <div class="col-md">
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="name">
                                @if ($errors->has('name'))
                                    <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <!-- Parent Category Dropdown -->
                            <div class="col-md">
                                <label class="form-label">Parent Category</label>
                                <select class="form-select" name="parent_category">
                                    <option value=""></option>
                                    @foreach ($allCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Description Input -->
                        <div class="mt-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control form-control-sm" name="description" rows="2"></textarea>
                            @if ($errors->has('description'))
                                <div class="text-danger mt-1">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                        <!-- Submission Button -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>

                <!-- Category Filter Form -->
                <div class="border p-3 bg-light rounded mt-4">
                    <form method="GET" action="{{ route('categories.index') }}">
                        <div class="row">
                            <!-- Name Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Filter by Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}">
                            </div>
                            <!-- Parent Category Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Filter by Parent Category</label>
                                <select class="form-select" name="parent_category">
                                    <option value="">All</option>
                                    @foreach ($allCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Filter Action Buttons -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-2">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Category Listing Table -->
                <div class="container mt-5">
                    <table class="table table-hover table-responsive">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-start ps-5">Category</th>
                                <th scope="col" class="text-start">Description</th>
                                <th scope="col" class="text-start">Parent</th>
                                <th scope="col" class="text-end pe-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($categories->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No categories found for this search.
                                    </td>
                                </tr>
                            @else
                                @foreach ($categories as $category)
                                    <tr>
                                        <!-- Display Category Name -->
                                        <td>{{ $category->name }}</td>
                                        <!-- Display Category Description -->
                                        <td class="text-muted small">{{ $category->description }}</td>
                                        <!-- Display Parent Category as Badge -->
                                        <td>
                                            @if ($category->parentCategory)
                                                <span
                                                    class="badge bg-secondary">{{ $category->parentCategory->name }}</span>
                                            @endif
                                        </td>
                                        <!-- Action Buttons: Edit and Delete -->
                                        <td class="text-end gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $category->id }}">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $category->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <!-- Include the Edit Category Modal for this category -->
                                            @include('components.edit-category-modal', [
                                                'category' => $category,
                                            ])
                                            <!-- Include a Confirmation Modal for Deletion -->
                                            @include('components.confirmation-modal', [
                                                'modalId' => "deleteModal{$category->id}",
                                                'title' => 'Confirm Delete',
                                                'message' => 'Are you sure you want to delete this category?',
                                                'action' => route('categories.destroy', $category->id),
                                                'method' => 'DELETE',
                                                'confirmButton' => 'Delete',
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
