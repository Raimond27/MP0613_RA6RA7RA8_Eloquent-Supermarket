<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal header with title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the login form -->
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Hidden field to keep track of redirection URL after login -->
                    <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                    <!-- Email input field -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <!-- Password input field -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <!-- Login button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <!-- Link to register modal if the user doesn't have an account -->
                <p class="text-center mt-3">
                    Don't have an account yet?
                    <a href="#" onclick="this.blur();" data-bs-toggle="modal" data-bs-target="#registerModal"
                        data-bs-dismiss="modal">
                        Register here.
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
```
