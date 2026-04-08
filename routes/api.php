<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('plans', App\Http\Controllers\Api\PlanController::class);

    Route::prefix('subscriptions')->group(function () {
        Route::get('/current', [SubscriptionController::class, 'current']);
        Route::post('/', [SubscriptionController::class, 'subscribe']);
        Route::get('/{subscription}', [SubscriptionController::class, 'show']);
        Route::post('/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
    });


    Route::prefix('subscriptions/{subscription}/payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/success', [PaymentController::class, 'recordSuccess']);
        Route::post('/failure', [PaymentController::class, 'recordFailure']);
    });




Route::post('login', [App\Http\Controllers\Api\UserController::class, 'login']);
Route::put('user/update/{id}', [App\Http\Controllers\Api\UserController::class, 'update']);
