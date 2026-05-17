<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\PaymentController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('schools', [SchoolController::class, 'index']);
    Route::get('schools/{school}', [SchoolController::class, 'show']);

    Route::prefix('parents')->group(function () {
        Route::get('children', [ParentController::class, 'children']);
        Route::post('children', [ParentController::class, 'storeChild']);
        Route::put('children/{child}', [ParentController::class, 'updateChild']);
        Route::post('ride-requests', [ParentController::class, 'storeRideRequest']);
        Route::get('ride-requests', [ParentController::class, 'rideRequests']);
        Route::get('rides', [ParentController::class, 'rides']);
        Route::get('rides/{ride}', [ParentController::class, 'showRide']);
        Route::get('notifications', [ParentController::class, 'notifications']);
    });

    Route::prefix('drivers')->group(function () {
        Route::get('profile', [DriverController::class, 'profile']);
        Route::put('profile', [DriverController::class, 'updateProfile']);
        Route::get('vehicles', [DriverController::class, 'vehicles']);
        Route::post('vehicles', [DriverController::class, 'storeVehicle']);
        Route::put('vehicles/{vehicle}', [DriverController::class, 'updateVehicle']);
        Route::get('available-rides', [DriverController::class, 'availableRides']);
        Route::post('rides/{ride}/accept', [DriverController::class, 'acceptRide']);
        Route::post('rides/{ride}/start', [DriverController::class, 'startRide']);
        Route::post('rides/{ride}/complete', [DriverController::class, 'completeRide']);
        Route::post('rides/{ride}/location', [DriverController::class, 'updateLocation']);
        Route::get('rides/active', [DriverController::class, 'activeRides']);
    });

    Route::prefix('admin')->group(function () {
        Route::get('drivers/pending', [AdminController::class, 'pendingDrivers']);
        Route::post('drivers/{driver}/approve', [AdminController::class, 'approveDriver']);
        Route::post('drivers/{driver}/reject', [AdminController::class, 'rejectDriver']);
        Route::post('schools', [AdminController::class, 'storeSchool']);
        Route::put('schools/{school}', [AdminController::class, 'updateSchool']);
    });

    Route::get('ride-status/{ride}', [RideController::class, 'status']);

    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('create', [PaymentController::class, 'create']);
        Route::get('{payment}/status', [PaymentController::class, 'status']);
        Route::post('notify', [PaymentController::class, 'notify']);
    });
});
