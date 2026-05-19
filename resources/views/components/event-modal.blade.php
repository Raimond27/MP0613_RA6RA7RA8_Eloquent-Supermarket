<!-- Event Modal: Used for creating or editing calendar events -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Begin form: Choose update or create mode based on $editEvent existence -->
            @if (isset($editEvent))
                <!-- Update mode: use PUT method -->
                <form method="POST"
                    action="{{ route('calendar.update', ['calendar' => $editEvent->id, 'year' => $year, 'month' => $month]) }}">
                    @method('PUT')
                @else
                    <!-- Creation mode -->
                    <form method="POST" action="{{ route('calendar.store', ['year' => $year, 'month' => $month]) }}">
            @endif
            @csrf

            <!-- Modal Header with dynamic title and total price badge -->
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">
                    {{ isset($editEvent) ? 'Edit Event' : 'Create Event' }}
                    <!-- Total Price Label shows computed total from selected products -->
                    <span id="totalPriceLabel" class="badge bg-info ms-2">Total: $0.00</span>
                </h5>
                <!-- Close button: Navigates back to calendar index (clears any edit query) -->
                <a href="{{ route('calendar.index', ['year' => $year, 'month' => $month]) }}" class="btn-close"></a>
            </div>

            <!-- Modal Body: Contains event details and product selections -->
            <div class="modal-body">
                <!-- Event Description Input -->
                <div class="mb-3">
                    <label for="eventDescription" class="form-label">Event Description</label>
                    <input type="text" class="form-control" name="description" id="eventDescription"
                        value="{{ isset($editEvent) ? $editEvent->description : '' }}" required>
                </div>
                <!-- Date Picker for event occurrence -->
                <div class="mb-3">
                    <label for="eventDate" class="form-label">Select Date</label>
                    <input type="date" class="form-control" name="next_occurrence" id="eventDate"
                        value="{{ isset($editEvent) ? ($editEvent->next_occurrence ? $editEvent->next_occurrence->format('Y-m-d') : '') : sprintf('%04d-%02d-01', $year, $month) }}"
                        required>
                </div>
                <!-- Recurrence Dropdown -->
                <div class="mb-3">
                    <label for="eventRecurrence" class="form-label">Recurrence</label>
                    <select class="form-select" name="recurrence_type" id="eventRecurrence">
                        <option value="none"
                            {{ isset($editEvent) && $editEvent->recurrence_type === 'none' ? 'selected' : '' }}>
                            None
                        </option>
                        <option value="monthly"
                            {{ isset($editEvent) && $editEvent->recurrence_type === 'monthly' ? 'selected' : '' }}>
                            Monthly
                        </option>
                        <option value="yearly"
                            {{ isset($editEvent) && $editEvent->recurrence_type === 'yearly' ? 'selected' : '' }}>
                            Yearly
                        </option>
                    </select>
                </div>
                <!-- Products Selection Block -->
                <div class="mb-3">
                    <label class="form-label">Products to Buy</label>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 5px;">
                        @foreach ($products as $product)
                            <!-- Each product is represented with a checkbox and a quantity input -->
                            <div class="form-check mb-2">
                                <input class="form-check-input product-checkbox" type="checkbox" name="products[]"
                                    value="{{ $product->id }}" id="product{{ $product->id }}"
                                    data-final-price="{{ $product->final_price }}"
                                    @if (isset($editEvent) && $editEvent->products->pluck('id')->contains($product->id)) checked @endif>
                                <label class="form-check-label" for="product{{ $product->id }}">
                                    {{ $product->name }}
                                </label>
                                <!-- Quantity Selector for product purchase -->
                                <input type="number" min="1" class="form-control product-qty"
                                    name="quantities[{{ $product->id }}]"
                                    value="{{ isset($editEvent) && $editEvent->products->pluck('id')->contains($product->id) ? $editEvent->products->find($product->id)->pivot->quantity : 1 }}"
                                    style="width: 70px; display: inline-block; margin-left: 10px;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- .modal-body ends here -->

            <!-- Modal Footer with Cancel and Submit buttons -->
            <div class="modal-footer">
                <a href="{{ route('calendar.index', ['year' => $year, 'month' => $month]) }}"
                    class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($editEvent) ? 'Save Changes' : 'Save Event' }}
                </button>
            </div>
            </form>

            <!-- Conditional Delete Form: Only available in edit mode -->
            @if (isset($editEvent))
                <form method="POST"
                    action="{{ route('calendar.destroy', ['calendar' => $editEvent->id, 'year' => $year, 'month' => $month]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Script to dynamically update the total price based on selected products and their quantities -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const totalPriceLabel = document.getElementById('totalPriceLabel');

        // Function to update the total price by iterating over selected products
        function updateTotalPrice() {
            let total = 0;
            productCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    // Locate the quantity input within the same form-check area
                    const qtyInput = checkbox.closest('.form-check').querySelector('.product-qty');
                    const qty = parseFloat(qtyInput.value) || 1;
                    const price = parseFloat(checkbox.getAttribute('data-final-price')) || 0;
                    total += (qty * price);
                }
            });
            totalPriceLabel.textContent = 'Total: $' + total.toFixed(2);
        }

        // Attach event listeners to checkboxes and quantity inputs for live update
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", updateTotalPrice);
        });
        const qtyInputs = document.querySelectorAll('.product-qty');
        qtyInputs.forEach(input => {
            input.addEventListener("change", updateTotalPrice);
            input.addEventListener("input", updateTotalPrice);
        });

        // Update price on page load in case of pre-selected products
        updateTotalPrice();
    });
</script>
