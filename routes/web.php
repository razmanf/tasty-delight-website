<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\LogoutController;
use App\Livewire\UserDashboard;
use App\Livewire\User\UserOrders;
use App\Livewire\User\UserFavorites;
use App\Livewire\User\UserCart;
use App\Livewire\User\UserReviews;
use App\Livewire\User\UserNotifications;
use App\Livewire\User\UserSettings;
use App\Livewire\User\UserSearch;
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

// ─── Root / Login / Register ─────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('filament.admin.pages.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return view('auth.register');
});

Route::get('/user/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');

// ─── Static Pages ─────────────────────────────────────────────────────────────

Route::get('/privacy',      [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms',        [PageController::class, 'terms'])->name('terms');
Route::get('/about',        [PageController::class, 'about'])->name('about');
Route::get('/contact',      [PageController::class, 'contact'])->name('contact');

// ─── Testing ──────────────────────────────────────────────────────────────────
Route::get('/test-relationships', [TestController::class, 'testRelationships']);
Route::get('/test-email',         [TestController::class, 'testEmail']);
Route::get('/test-verification',  [TestController::class, 'testVerification']);

// ─── Logout ───────────────────────────────────────────────────────────────────
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// ─── Fortify Login Routes ─────────────────────────────────────────────────────
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest']);

// ─── Pre-Registration OTP Route ────────────────────────────────────────────────
Route::post('/send-registration-otp', [App\Http\Controllers\Auth\RegistrationOtpController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('registration.otp.send');

// ─── Post-login Dashboard Redirect ───────────────────────────────────────────
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

// ─── User Panel Routes (protected — users only, admins redirected out) ────────
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'user',
])->group(function () {
    Route::get('/user/dashboard',     UserDashboard::class)->name('user.dashboard');
    Route::get('/user/menu',          \App\Livewire\User\UserMenu::class)->name('user.menu');
    Route::get('/user/orders',        UserOrders::class)->name('user.orders');
    Route::get('/user/favorites',     UserFavorites::class)->name('user.favorites');
    Route::get('/user/cart',          UserCart::class)->name('user.cart');
    Route::get('/user/reviews',       UserReviews::class)->name('user.reviews');
    Route::get('/user/notifications', UserNotifications::class)->name('user.notifications');
    Route::get('/user/settings',      UserSettings::class)->name('user.settings');
    Route::get('/user/search',        UserSearch::class)->name('user.search');
    Route::get('/user/checkout',      \App\Livewire\User\UserCheckout::class)->name('user.checkout');

    // Redirect /user/profile (old Jetstream route) to our new settings page
    Route::get('/user/profile', fn () => redirect()->route('user.settings'));
});