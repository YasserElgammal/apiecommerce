<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\MarketController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
I've identified the version so it will be easy to handle the version number,
especially for mobile apps
*/
Route::prefix('v1')->group(function () {

    // Echo
    Route::get('/', function () {
        return response(['success'=> true,'version'=> '1.0']);

    });

    // public routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Special To Merchant Only
    Route::middleware(['auth:sanctum','abilities:merchant:roles'])->prefix('merchant')->group(function () {
        Route::get('index', [MarketController::class, 'index']);
        Route::patch('set-name', [MarketController::class, 'setStoreName']);
        Route::patch('set-vat-options', [MarketController::class, 'setVatOptions']);
        Route::post('add-product', [ProductController::class, 'addProduct']);
    });

    // Special To User"Customers" Only
    Route::middleware(['auth:sanctum','abilities:user:roles', 'checkLang'])->prefix('customer')->group(function () {
        Route::post('add-item-cart', [ItemController::class, 'addItemCart']);
        Route::get('get-cart-items', [CartController::class, 'getCartItems']);
        Route::get('list-all-products', [ProductController::class, 'getAllProducts']);
    });
});

