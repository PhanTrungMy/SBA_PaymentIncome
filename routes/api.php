<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CategoryController;

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
});
Route::group([
    'prefix' => 'auth',
    'middleware' => [
        'checkLogin',
    ],
], function () {
    Route::post("logout", [AuthController::class, "logout"])->name('logout');
});
Route::group([
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {

    Route::post('/categories', [CategoryController::class, 'catogory_create']);
    Route::get('/categories', [CategoryController::class, 'catogory_show_all']);
    Route::get('/categories/{id}', [CategoryController::class, 'catogory_show_id']);
    Route::put('/categories/{id}', [CategoryController::class, 'catogory_update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'catogory_delete']);
});
// Route::middleware('auth:api')->group(function () {
//     Route::post('/categories', [CategoryController::class, 'store']);
//     Route::get('/categories', [CategoryController::class, 'index']);
//     Route::get('/categories/{id}', [CategoryController::class, 'show']);
// });
