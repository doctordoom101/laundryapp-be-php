<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OutletController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\LaundryItemController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\ReportController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/laundry-items/check/{code}', [LaundryItemController::class, 'checkStatus']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('outlets', OutletController::class);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
    
    // Admin and Owner routes
    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/reports', [ReportController::class, 'index']);
    });
    
    // Admin and Petugas routes
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/laundry-items', [LaundryItemController::class, 'index']);
        Route::post('/laundry-items', [LaundryItemController::class, 'store']);
        Route::patch('/laundry-items/{laundryItem}/status', [LaundryItemController::class, 'updateStatus']);
        Route::get('/transactions', [TransactionController::class, 'index']);
    });
    
    // Petugas only routes
    // Route::middleware('role:petugas')->group(function () {
    //     Route::get('/laundry-items', [LaundryItemController::class, 'index']);
    //     Route::post('/laundry-items', [LaundryItemController::class, 'store']);
    //     Route::patch('/laundry-items/{laundryItem}/status', [LaundryItemController::class, 'updateStatus']);
    //     Route::post('/transactions', [TransactionController::class, 'store']);
    // });
    
    // All authenticated users
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/laundry-items/{laundryItem}', [LaundryItemController::class, 'show']);
});