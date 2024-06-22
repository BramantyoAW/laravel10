<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Api\ProductController;

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

Route::get('/shop/install',[ShopController::class, 'install']);
Route::get('/shop',[ShopController::class, 'index']);
Route::post('/shop',[ShopController::class, 'shop']);
Route::get('/shopshow',[ShopController::class, 'showShop']);


#assestment
Route::post('/product/push',[ProductController::class, 'productPush']);