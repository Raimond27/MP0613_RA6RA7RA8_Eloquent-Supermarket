<!-- Edit Product Modal -->
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Form to update product details using the PUT method -->
            <form method="POST" action="{{ route('products.update', $product->id) }}">
                @csrf
                @method('PUT')

                <!-- Modal header with title and close button -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $product->id }}">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal body containing product data fields -->
                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" id="productName{{ $product->id }}"
                            value="{{ $product->name }}" required>
                        <label for="productName{{ $product->id }}">Product Name</label>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="form-floating mb-3">
                        <select class="form-select" name="category_id" id="productCategory{{ $product->id }}" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="productCategory{{ $product->id }}">Category</label>
                    </div>

                    <!-- Description -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="description" id="productDescription{{ $product->id }}" rows="2">{{ $product->description }}</textarea>
                        <label for="productDescription{{ $product->id }}">Description</label>
                    </div>

                    <!-- Base Price -->
                    <div class="mb-3">
                        <label for="productPrice{{ $product->id }}" class="form-label">Base Price</label>
                        <input type="number" step="0.01" class="form-control" name="price"
                            id="productPrice{{ $product->id }}" value="{{ $product->price }}" required>
                    </div>

                    <!-- Fee Selection Block -->
                    <div class="border p-3 bg-light rounded mt-3">
                        <h5>Apply Fee</h5>

                        <!-- Fee Dropdown -->
                        <div class="mb-3">
                            <label for="feeId{{ $product->id }}" class="form-label">Select Fee</label>
                            <select class="form-select" name="fee_id" id="feeId{{ $product->id }}">
                                <option value="">None</option>
                                @foreach ($fees as $fee)
                                    <option value="{{ $fee->id }}"
                                        {{ optional($product->fees->first())->id == $fee->id ? 'selected' : '' }}>
                                        {{ $fee->name }} ({{ $fee->date_start }} - {{ $fee->date_end }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Variation Type (Radio Buttons) -->
                        <div class="mb-3">
                            <label class="form-label">Variation Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="variation_type"
                                    id="variationNumber{{ $product->id }}" value="number"
                                    {{ optional(optional($product->fees->first())->pivot)->variation_type == 'number' ? 'checked' : '' }}>
                                <label class="form-check-label" for="variationNumber{{ $product->id }}">Number</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="variation_type"
                                    id="variationPercentage{{ $product->id }}" value="percentage"
                                    {{ optional(optional($product->fees->first())->pivot)->variation_type == 'percentage' ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="variationPercentage{{ $product->id }}">Percentage</label>
                            </div>
                        </div>

                        <!-- Variation Amount -->
                        <div class="mb-3">
                            <label for="variationAmount{{ $product->id }}" class="form-label">Variation Amount</label>
                            <input type="number" step="0.01" class="form-control" name="variation_amount"
                                id="variationAmount{{ $product->id }}"
                                value="{{ optional(optional($product->fees->first())->pivot)->variation_ammount }}">
                        </div>
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
