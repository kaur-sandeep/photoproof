<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;

Route::prefix('v1')->group(base_path('routes/api_v1.php'));
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/upload_photo', [AuthController::class, 'uploadPhoto']);
    Route::get('/photos', [AuthController::class, 'getPhotos']);
    // Route::get('/search_photo/{random_id}', [AuthController::class, 'search_photo']);
    Route::post('/search_photo', [AuthController::class, 'search_photo']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/plans', [PlanController::class, 'plans'])->name('plans');
    

// });
// Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
// Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
//     ->middleware(['signed'])
//     ->name('verification.verify');