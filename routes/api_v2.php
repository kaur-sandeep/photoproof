<?php
use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\PlanController;
use App\Http\Controllers\Api\V2\InstallController;
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/upload_photo', [AuthController::class, 'uploadPhoto']);
   
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/plans', [PlanController::class, 'plans'])->name('plans');
    Route::get('/test_version', [AuthController::class, 'testVersion']);
    Route::post('/track-install', [InstallController::class, 'trackInstall']);
    Route::post('/request_otp', [AuthController::class, 'requestOtp']);
    Route::post('/verify_otp', [AuthController::class, 'verifyOtp']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/employee/upload-photo', [AuthController::class, 'employeeUploadPhoto']);
         Route::get('/photos', [AuthController::class, 'getPhotos']);
         Route::post('/logout', [AuthController::class, 'logout']);
         Route::get('/cities', [AuthController::class, 'getCities']);
         Route::post('/delete-account', [AuthController::class, 'deleteAccount']);
    });
    
// });
// Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
// Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
//     ->middleware(['signed'])
//     ->name('verification.verify');