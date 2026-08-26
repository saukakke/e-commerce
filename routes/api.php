<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status'=>'ok','service'=>'e-commerce']));
Route::get('/products', function(Request $request){
    return response()->json(\App\Models\Product::where('status',true)->paginate($request->integer('per_page',12)));
});