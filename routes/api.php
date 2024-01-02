<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PaymentOrderController;
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
    // groups all
    Route::get('groups', [GroupController::class, "get_all_groups"])->name('groups');
});
Route::group([
    'prefix' => 'payment_orders',
    'middleware' => [
        'checkLogin',
         'verifyToken',
    ],
], function () {
    // payments
    Route::get('', [PaymentOrderController::class, "get_all_payment_orders"])->name('payments');
    Route::post('', [PaymentOrderController::class, "create_payment_order"])->name('create_payments');
    Route::put('{id}', [PaymentOrderController::class, "update_payment_order"])->name('update_payments');
    Route::delete('{id}', [PaymentOrderController::class, "delete_payment_order"])->name('delete_payments');
});
Route::group([
    'prefix' => 'payment_orders',
    'middleware' => [
        'checkLogin',
        'verifyToken'
    ],
], function () {
    //payments Id
    Route::get("{id}", [PaymentOrderController::class, "PaymentOrderId"])->name('payments_id');
});
Route::group([
    'prefix' => 'categories',
    'middleware' => [
        'checkLogin',
         'verifyToken',
    ],
], function () {
    // categories
    Route::post('', [CategoryController::class, 'store']);
    Route::get('', [CategoryController::class, 'index']);
    Route::get('{id}', [CategoryController::class, 'show']);
    Route::put('{id}', [CategoryController::class, 'update']);
});

