<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;

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
    Route::post('/categories', [CategoryController::class, 'catogory_create']);
    Route::get('/categories', [CategoryController::class, 'catogory_show_all']);
    Route::get('/categories/{id}', [CategoryController::class, 'catogory_show_id']);
    Route::put('/categories/{id}', [CategoryController::class, 'catogory_update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'catogory_delete']);
});
Route::group([
    'prefix' => 'payment_orders',
    'middleware' => [
        'checkLogin',
         'verifyToken',
    ],
], function () {
    // payment_orders
    Route::get('', [PaymentOrderController::class, "get_all_payment_orders"])->name('payments');
    Route::post('', [PaymentOrderController::class, "create_payment_order"])->name('create_payment_orders');
    Route::put('{id}', [PaymentOrderController::class, "update_payment_order"])->name('update_payment_orders');
    Route::delete('{id}', [PaymentOrderController::class, "delete_payment_order"])->name('delete_payment_orders');
});
Route::group([
    'prefix' => 'payment_orders',
    'middleware' => [
        'checkLogin',
        'verifyToken'
    ],
], function () {
    //payment_order Id
    Route::get("{id}", [PaymentOrderController::class, "PaymentOrderId"])->name('payments_order_id');
});
Route::group([
    'prefix' => 'payments',
    'middleware' => [
        'checkLogin',
         'verifyToken',
    ],
], function () {
    // payments
    Route::post('', [PaymentController::class, "create_payments"])->name('create_payments');

});
Route::group([
    'prefix' => 'payment_orders',
    'middleware' => [
        'checkLogin',
        'verifyToken'
    ],
], function () {
    //payments Id

});

Route::group([
    'prefix' => 'categories',
    'middleware' => [
        'checkLogin',
         'verifyToken',
    ],
], function () {
    // categories
    Route::post('', [CategoryController::class, 'catogory_create']);
    Route::get('', [CategoryController::class, 'catogory_show_all']);
    Route::get('{id}', [CategoryController::class, 'catogory_show_id']);
    Route::put('{id}', [CategoryController::class, 'catogory_update']);
    Route::delete('{id}', [CategoryController::class, 'catogory_delete']);
});

Route::group([
    'prefix' => 'orders',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // categories
    Route::get('', [OrderController::class, 'Get_all_order']);
    Route::get('{id}', [OrderController::class, 'Get_Order_By_ID']);
    Route::post('', [OrderController::class, 'Create_Order']);
    Route::put('{id}', [OrderController::class, 'Update_Order']);
    Route::delete('{id}', [OrderController::class, 'Delete_Order']);
});

