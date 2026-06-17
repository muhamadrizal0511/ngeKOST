<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KostController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/kost', [KostController::class, 'index']);
Route::get('/kost/{id}', [KostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/kost', [KostController::class, 'store']);

    Route::put('/kost/{id}', [KostController::class, 'update']);

    Route::delete('/kost/{id}', [KostController::class, 'destroy']);

    Route::get('/owner/kost', [KostController::class, 'ownerKost']);

    Route::post('/booking', [BookingController::class, 'store']);

    Route::get('/my-bookings', [BookingController::class, 'myBookings']);

    Route::get('/owner/bookings', [BookingController::class, 'ownerBookings']);

    Route::put('/booking/{id}/approve', [BookingController::class, 'approve']);

    Route::put('/booking/{id}/reject', [BookingController::class, 'reject']);
    
    Route::post('/payment/upload', [PaymentController::class, 'upload']);

    Route::put('/payment/{id}/verify', [PaymentController::class, 'verify']);
});