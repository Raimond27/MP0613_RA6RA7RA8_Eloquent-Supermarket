<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supermarket Landing Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Brand / Logo with link to landing page -->
            <a class="navbar-brand" href="{{ route('landing') }}">Supermarket</a>
            <!-- Navbar Toggler for mobile view -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Collapsible Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Home link -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}">Home</a></li>
                    <!-- Products link -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                    <!-- Additional links for authenticated employees -->
                    @if (Auth::check() && Auth::user()->hasAccess('employee_access'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('fees.index') }}">Fees</a></li>
                    @endif
                    <!-- Calendar link for all authenticated users -->
                    @if (Auth::check())
                        <li class="nav-item"><a class="nav-link" href="{{ route('calendar.index') }}">Calendar</a></li>
                    @endif
                </ul>
            </div>
            <!-- Authentication Button(s) -->
            @if (Auth::check())
                <!-- Logout form for authenticated users -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            @else
                <!-- Login button triggering the login modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Login
                </button>
            @endif
        </div>
    </nav>

    <!-- Main Content: Rendered from child views -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Supermarket Inc. All rights reserved.</p>
    </footer>

    <!-- Include Login and Register Modals -->
    @include('auth.login-modal')
    @include('auth.register-modal')

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Additional custom scripts can be pushed here -->
    @stack('scripts')
</body>

</html>
