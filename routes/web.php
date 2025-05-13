<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Frontend\CarController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\CustomAuthController;

use App\Http\Controllers\Frontend\RentalController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// Car Routes
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
Route::post('/cars/{car}/check-availability', [CarController::class, 'checkAvailability'])->name('cars.check-availability');

// Authentication Routes
Auth::routes();

// Protected Routes for Authenticated Users
Route::middleware(['auth'])->group(function () {
    // Rental Routes
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/cars/{car}/rent', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/cars/{car}/rent', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
});
// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Add this settings route
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    Route::post('/settings', function (Request $request) {
    // Validate and save settings
    $request->validate([
        'site_name' => 'required|string|max:255',
        'contact_email' => 'required|email',
        'contact_phone' => 'required|string|max:20',
    ]);


    return redirect()->route('admin.settings')->with('success', 'Settings updated successfully');
})->name('settings.update');

    // Car Management
    Route::resource('cars', AdminCarController::class);

    // Rental Management
    Route::resource('rentals', AdminRentalController::class);

    // Customer Management
    Route::resource('customers', AdminCustomerController::class);

    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/rentals', [ReportController::class, 'rentals'])->name('reports.rentals');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/cars-performance', [ReportController::class, 'carsPerformance'])->name('reports.cars-performance');
    Route::get('/reports/customer-analysis', [ReportController::class, 'customerAnalysis'])->name('reports.customer-analysis');
    Route::post('/reports/export-pdf', [ReportController::class, 'generatePdf'])->name('reports.export-pdf');
    Route::post('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
});


// This demonstrates the auth routes for the car rental application
// Authentication Routes
Auth::routes(['verify' => true]);

// Custom Auth Routes
Route::get('/profile', [CustomAuthController::class, 'showProfileForm'])->name('profile.show');
Route::put('/profile', [CustomAuthController::class, 'updateProfile'])->name('profile.update');
Route::get('/profile/edit', [CustomAuthController::class, 'editProfile'])->name('profile.edit');


// Custom Registration Routes (if needed)
Route::get('/register-driver', [CustomAuthController::class, 'showRegistrationForm'])->name('register.driver');
Route::post('/register-driver', [CustomAuthController::class, 'register'])->name('register.driver.submit');


// Customer Routes (protected by auth middleware)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::get('/cars/{car}/rent', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/cars/{car}/rent', [RentalController::class, 'store'])->name('rentals.store');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
});


