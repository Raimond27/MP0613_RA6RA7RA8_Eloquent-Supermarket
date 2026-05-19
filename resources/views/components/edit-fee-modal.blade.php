<!-- Edit Fee Modal -->
<div class="modal fade" id="editModal{{ $fee->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $fee->id }}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Form to update fee details using the PUT method -->
            <form method="POST" action="{{ route('fees.update', $fee->id) }}">
                @csrf
                @method('PUT')

                <!-- Modal header with title and a close button -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $fee->id }}">Edit Fee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body containing the form inputs -->
                <div class="modal-body">
                    <!-- Fee Name input field -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" id="feeName{{ $fee->id }}"
                            value="{{ $fee->name }}">
                        <label for="feeName{{ $fee->id }}">Fee Name</label>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <!-- Start Date input field -->
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="date_start" id="startDate"
                                    value="{{ $fee->date_start }}" required>
                                <label for="startDate" class="form-label">Start Date</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <!-- End Date input field -->
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="date_end" id="endDate"
                                    value="{{ $fee->date_end }}" required>
                                <label for="endDate" class="form-label">End Date</label>
                            </div>
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
