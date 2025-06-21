<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('user/update',[AuthController::class,'update'])->middleware('auth:sanctum');


// Category routes


Route::get('categories', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'store']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);


// Product routes
Route::post('product',[ProductController::class,'store']);
Route::get('products',[ProductController::class,'index']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::get('getproductbycat/{id}',[ProductController::class,'getProductByCategroy']);
Route::get('searchproduct',[ProductController::class,'search']);


// Cart routes
Route::post('cart',[CartController::class,'addToCart'])->middleware('auth:sanctum');
Route::get('viewCart',[CartController::class,'viewCart'])->middleware('auth:sanctum');
Route::post('remove-cart-item/{proId}',[CartController::class,'removeFromCart'])->middleware('auth:sanctum');
Route::post('cart/clear',[CartController::class,'clearCart'])->middleware('auth:sanctum');

// address routes
Route::post('address',[AddressController::class,'store'])->middleware('auth:sanctum');
Route::put('address/{id}',[AddressController::class,'update'])->middleware('auth:sanctum');
Route::get('address',[AddressController::class,'index'])->middleware('auth:sanctum');
Route::delete('address/{id}',[AddressController::class,'destroy'])->middleware('auth:sanctum');

//order routes

Route::post('orders',[OrderController::class,'store'])->middleware('auth:sanctum');
Route::get('orders',[OrderController::class,'index'])->middleware('auth:sanctum');
Route::post('checkout',[OrderController::class,'checkout'])->middleware('auth:sanctum');

// profile routes
Route::get('profile',[AuthController::class,'profile'])->middleware('auth:sanctum');
Route::post('profile/update',[AuthController::class,'update'])->middleware('auth:sanctum');
Route::post('profile/delete',[AuthController::class,'delete'])->middleware('auth:sanctum');


// ... other routes

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('user/update',[AuthController::class,'update'])->middleware('auth:sanctum');

// Add this line for changing the password
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');


