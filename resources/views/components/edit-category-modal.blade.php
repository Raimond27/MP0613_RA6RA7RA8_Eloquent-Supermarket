<!-- Edit Category Modal -->
<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category->id }}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Form that submits the updated category data using the PUT method -->
            <form method="POST" action="{{ route('categories.update', $category->id) }}">
                @csrf
                @method('PUT')

                <!-- Modal header with title and close button -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $category->id }}">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body containing form elements -->
                <div class="modal-body">
                    <!-- Category Name input field -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" id="categoryName{{ $category->id }}"
                            value="{{ $category->name }}">
                        <label for="categoryName{{ $category->id }}">Category Name</label>
                    </div>

                    <!-- Parent Category dropdown; excludes the current category -->
                    <div class="form-floating mb-3">
                        <select class="form-select" name="parent_category" id="parentCategory{{ $category->id }}">
                            <option value="">None</option>
                            @foreach ($allCategories->where('id', '!=', $category->id) as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $category->parent_category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="parentCategory{{ $category->id }}">Parent Category</label>
                    </div>

                    <!-- Description textarea for editing category details -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="description" id="categoryDescription{{ $category->id }}" rows="2">{{ $category->description }}</textarea>
                        <label for="categoryDescription{{ $category->id }}">Description</label>
                    </div>
                </div>

                <!-- Modal footer with Cancel and Save Changes buttons -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
