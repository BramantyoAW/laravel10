<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\RedirController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

#training
// Route::get('/',[ShopController::class, 'index']);
// Route::get('/install',[ShopController::class, 'install']);
// Route::get('/token',[ShopController::class, 'getToken']);

#assesment
Route::get('/', function () {
    return view('welcome_shopify');
});
Route::get('/install',[InstallController::class, 'index']);
Route::get('/redir',[RedirController::class, 'index']);