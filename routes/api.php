<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
user login section
 */
Route::prefix('user')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('reset_password', [UserController::class, 'resetPassword']);
    Route::post('forgot_password',[UserController::class, 'forgotPassword']);
    Route::post('verify',[UserController::class, 'verifyAccount']);
    Route::post('logout',[UserController::class, 'logout']);
});
