<?php

use App\Http\Controllers\{ApiAuthController,ApiController,PlatformController};
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:10,1')->post('/auth/login',[ApiAuthController::class,'login']);
Route::middleware('throttle:30,1')->post('/payment/paystack/webhook',[PlatformController::class,'webhook']);
Route::middleware('throttle:120,1')->group(function(){
    Route::get('/health',[ApiController::class,'health']);
    Route::get('/products',[ApiController::class,'products']);
    Route::get('/products/{product}',[ApiController::class,'product']);
});
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/me',[ApiAuthController::class,'user']);
    Route::post('/auth/logout',[ApiAuthController::class,'logout']);
    Route::middleware('throttle:120,1')->group(function(){Route::get('/orders',[ApiController::class,'orders']);Route::get('/orders/{order}',[ApiController::class,'order']);});
});
