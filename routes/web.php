<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\LogoutController;
use App\Livewire\UserDashboard;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PageController;

// Welcome page
Route::get('/', function () {
    return view('auth.register');
});

// Custom Login Routes
Route::get('/user/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');

// Static Pages
Route::get('/employees', [PageController::class, 'employees']);
Route::get('/appointments', [PageController::class, 'appointments']);
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Testing routes
Route::get('/test-relationships', [TestController::class, 'testRelationships']);
Route::get('/test-email', [TestController::class, 'testEmail']);
Route::get('/test-verification', [TestController::class, 'testVerification']);

// Logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Default Jetstream login GET route
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('login');

// Default Jetstream login POST route
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest']);
    
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'), // in practice this is `web`
    'verified',
    'admin', // or 'user'
])->prefix('admin')->name('admin.')->group(function () {
    // OLD DASHBOARD - Disabled to prevent conflicts with Filament panel at /admin
    // Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
    // Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    // Route::get('users', [AdminUserController::class, 'index'])->name('users.index');

    // Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    // Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    // Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    // Route::resource('reviews', ReviewController::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'user'
])->group(function () {
    Route::get('/user/dashboard', UserDashboard::class)->name('user.dashboard');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('filament.admin.pages.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->name('dashboard');