<?php
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
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
        Route::post('/profile/update', [LoginController::class, 'profileUpdate'])->name('admin.profile.update');
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/users/list', [UserController::class, 'list'])->name('admin.users.list');
        Route::post('/users/update-status', [UserController::class, 'admin.user.updateStatus']);
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/edit/{userId}', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/users/update/{userId}', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('/user/update/status', [UserController::class, 'updateStatus']);
        
       

        
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    });

});
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('admin.forgot-password');
    Route::post('/send-password-reset-link', [LoginController::class, 'sendPasswordRestLink'])->name('admin.send-password-reset-link');
    Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/reset/password', [LoginController::class, 'resetPassword'])->name('admin.reset.password');
    Route::get('/link/expired', [LoginController::class, 'expireLink'])->name('admin.password.expired');
    Route::get('/reset-password', [LoginController::class, 'showUserResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPasswordUser'])->name('password.update');



