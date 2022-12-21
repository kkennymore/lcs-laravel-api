<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('dashmin/index');
    });
    Route::get('/products', function () {
        return view('products');
    });
    Route::get('/orders', function () {
        return view('orders');
    });
    Route::get('/cart', function () {
        return view('cart');
    });
});
