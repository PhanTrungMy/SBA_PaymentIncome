<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ExchangeRateController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'prefix' => 'auth',
     'middleware' => 'api',
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::group([
    'prefix' => 'auth',
     'middleware' => 'api',
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    
});

Route::group([
    'prefix' => 'exchangeRate',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    Route::get('{month}/{year}', [ExchangeRateController::class, 'ExchangeRateByMonthYear']);
    Route::post('', [ExchangeRateController::class, "CreateExchangeRate"]);
    Route::put('{id}', [ExchangeRateController::class, 'UpdateChangeRate']);
});