<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class,'home'])->name('home');
Route::get('/products', [ShopController::class,'products'])->name('products');
Route::get('/products/{product:slug}', [ShopController::class,'show'])->name('products.show');
Route::get('/cart', [CartController::class,'index'])->name('cart');
Route::post('/cart/{product}', [CartController::class,'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class,'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class,'remove'])->name('cart.remove');