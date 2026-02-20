<?php
use App\Http\Controllers\Api\AuthController;

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/upload_photo', [AuthController::class, 'uploadPhoto']);
    Route::get('/photos', [AuthController::class, 'getPhotos']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);

// });
// Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
// Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
//     ->middleware(['signed'])
//     ->name('verification.verify');