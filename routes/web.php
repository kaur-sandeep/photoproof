<?php
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
Route::get('/', function () {
    return redirect()->route('admin.login');
});
Route::prefix('admin')->group(function () {
    // Public Routes
    Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:admin')->group(function () {

        Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/profile', [LoginController::class, 'profile'])->name('admin.profile');
        Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    });

});
Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('admin.forgot-password');
Route::post('/send-password-reset-link', [LoginController::class, 'sendPasswordRestLink'])->name('admin.send-password-reset-link');
Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/reset/password', [LoginController::class, 'resetPassword'])->name('admin.reset.password');
Route::get('/link/expired', [LoginController::class, 'expireLink'])->name('admin.password.expired');




