<?php

use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckPermissions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Landing page route
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Routes for user profile management (require authentication)
Route::middleware('auth')->group(function () {
    // Edit User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Update User Profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Delete User Profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Publicly accessible route to list products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Routes for product management requiring 'employee_access' permission
Route::middleware([CheckPermissions::class . ':employee_access'])->group(function () {
    // Export products as XLS
    Route::get('/products/export/xls', [ProductController::class, 'export'])->name('products.export');
    // Export a single product as PDF
    Route::get('/products/{product}/export-pdf', [ProductController::class, 'exportPdf'])->name('products.exportPdf');
    // Create a new product
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    // Update an existing product
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    // Delete a product
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Resource route for Category management; requires 'employee_access' permission
Route::resource('/categories', CategoryController::class)
    ->middleware(CheckPermissions::class . ':employee_access');

// Display the login view (public access)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Logout route to end authenticated sessions
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Calendar routes: only accessible by authenticated users
Route::middleware(['auth'])->group(function () {
    Route::resource('calendar', CalendarEventController::class);
})->name('calendar');

// Resource route for Fee management; restricted to users with 'employee_access' permission
Route::resource('/fees', FeeController::class)
    ->middleware(CheckPermissions::class . ':employee_access');

// Include additional authentication routes from the auth.php file
require __DIR__ . '/auth.php';
