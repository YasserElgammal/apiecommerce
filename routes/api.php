<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\ItemController;
use App\Http\Controllers\Api\V1\Merchant\MarketController;
use App\Http\Controllers\Api\V1\Merchant\ProductController as MerchantProductController;
use App\Http\Controllers\Api\V1\Customer\ProductController;
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
    Route::middleware(['auth:sanctum','abilities:merchant'])->prefix('merchant')->group(function () {

        Route::controller(MarketController::class)->group(function () {
            Route::get('index', 'index');
            Route::patch('set-name', 'setStoreName');
            Route::patch('set-vat-options', 'setVatOptions');
        });

        Route::resource('products', MerchantProductController::class);
    });

    // Special To User"Customers" Only
    Route::middleware(['auth:sanctum','abilities:customer', 'checkLang'])->prefix('customer')->group(function () {
        Route::post('add-item-cart', [ItemController::class, 'addItemCart']);
        Route::post('remove-item-cart', [ItemController::class, 'removeItemFromCart']);
        Route::get('get-cart-items', [CartController::class, 'getCartItems']);
        Route::get('list-cart', [CartController::class, 'listAllOrders']);
        Route::get('list-all-products', [ProductController::class, 'getAllProducts']);
    });
});

