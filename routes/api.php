<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OutsourcingController;
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
    Route::post('refresh', [AuthController::class, "refresh"]);
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
    'prefix' => 'groups',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // groups all
    Route::get('', [GroupController::class, 'get_all_groups'])->name('groups');
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
    Route::get('', [PaymentController::class, "get_all_payments"])->name('get_payments');
    Route::post('', [PaymentController::class, "create_payments"])->name('create_payments');
    Route::put('{id}', [PaymentController::class, "update_payments"])->name('update_payments');
    Route::delete('{id}', [PaymentController::class, "delete_payments"])->name('delete_payments');
});
Route::group([
    'prefix' => 'payments',
    'middleware' => [
        'checkLogin',
        'verifyToken'
    ],
], function () {
    //payments Id
    Route::get("{id}", [PaymentController::class, "paymentsId"])->name('payments_id');
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
    // Order
    Route::get('', [OrderController::class, 'Get_all_order']);
    Route::get('{id}', [OrderController::class, 'Get_Order_By_ID']);
    Route::post('', [OrderController::class, 'Create_Order']);
    Route::put('{id}', [OrderController::class, 'Update_Order']);
    Route::delete('', [OrderController::class, 'Delete_Order']);
});
Route::group([
    'prefix' => 'outsourcing',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // outsourcing
    Route::get('', [OutsourcingController::class, 'show_all_outsourcing']);
    Route::get('{id}', [OutsourcingController::class, 'show_id_outsourcing']);
    Route::post('', [OutsourcingController::class, 'create_outsourcing']);
    Route::put('{id}', [OutsourcingController::class, 'update_outsourcing']);
    Route::delete('{id}', [OutsourcingController::class, 'delete_outsourcing']);
});
Route::group([
    'prefix' => 'analytics',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // analytics
    Route::get('', [AnalyticController::class, 'category_analytics']);
});
Route::group([
    'prefix' => 'balances',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    //balance
    Route::post('', [BalanceSheetController::class, 'balance_create']);
    Route::get('', [BalanceSheetController::class, 'balance_get']);
});
Route::group([
    'prefix' => 'getDataPL',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // getDate
    Route::get('', [DataTableController::class, 'get_data_table_pl']);
});
Route::group([
    'prefix' => 'getDataBS',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // getDate
    Route::get('', [DataTableController::class, 'get_data_table_bs']);
});
Route::group([
    'prefix' => 'exchangeRate',
    'middleware' => [
        'checkLogin',
        'verifyToken',
    ],
], function () {
    // Exchangrate
    Route::get('', [ExchangeRateController::class, 'ExchangeRateByMonthYear']);
    Route::post('', [ExchangeRateController::class, "CreateExchangeRateOrEditExchangeRate"]);
});
