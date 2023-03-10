<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MarketController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
I've identified the version so it will be easy to handle the version number,
especially for mobile apps
*/
Route::prefix('v1')->group(function () {
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
});

