<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Form to submit modal action -->
            <form method="POST" action="{{ $action }}">
                @csrf
                @if ($method)
                    @method($method)
                @endif

                <!-- Modal header with title and close button -->
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body displaying the message -->
                <div class="modal-body">
                    <p class="text-center">{{ $message }}</p>
                </div>

                <!-- Modal footer with Cancel and Confirm buttons -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">{{ $confirmButton }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
