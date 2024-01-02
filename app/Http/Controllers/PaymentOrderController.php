<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentOrderController extends Controller
{
    public function get_all_payment_orders(Request $request)
    {
        $resultPayments = [];
        $perPage = $request->query('per_page') ?? 5;
        $curPage = $request->query('cur_page') ?? 1;
        $payment_order = DB::select("
        SELECT `id`,`user_id`, `company_name`,`jpy`,`vnd`,`usd`,`exchange_rate_id`,`payment_date`, `created_at`, `updated_at`, `deleted_at`
        FROM `payment_orders`
        LIMIT :limit OFFSET :offset
    ", ['limit' => $perPage, 'offset' => ($curPage - 1) * $perPage]);

        foreach ($payment_order as $payment) {
            $resultPayments[] = [
                "id" => $payment->id,
                'user_id' => $payment->user_id,
                'company_name' => $payment->company_name,
                'jpy' => $payment->jpy,
                'vnd' => $payment->vnd,
                'usd' => $payment->usd,

                'exchange_rate_id' => $payment->exchange_rate_id,
                'payment_date' => $payment->payment_date,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
                'deleted_at' => $payment->deleted_at,
            ];
        }

        if (!$resultPayments) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }

        $totalPaymentOrder = DB::table('payment_orders')->count();
        $totalPages = ceil($totalPaymentOrder / $perPage);
        $pagination = [
            'per_page' => $perPage,
            'current_page' => $curPage,
            'total_pages' => $totalPages,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Get payment_orders successfully',
            'total_result' => $totalPaymentOrder,
            'pagination' => $pagination,
            'payment_orders' => $resultPayments
        ], 200);
    }
    public function PaymentOrderId(Request $request, $id)
    {
        $id = $request->route('id');
        $query = 'SELECT * FROM payment_orders WHERE id = ? AND deleted_at IS NULL ORDER BY id ASC';
        $payment_order = DB::select($query, [$id]);
        if ($payment_order == null) {
            return response()->json([
                "success" => false,
                "message" => "Payment_order not found",
            ], 404);
        }
        if (!$payment_order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
        return response()->json([
            "success" => true,
            "message" => "Get payment_order successfully",
            "payment_order" => $payment_order
        ], 200);
    }
    public function create_payment_order(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required',
            'company_name' => 'required',
            'jpy' => 'required',
            'vnd' => 'required',
            'usd' => 'required',
            'exchange_rate_id' => 'required',
            'payment_date' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validatedData->errors()
            ], 400);
        }
        $payment_order = PaymentOrder::create(
            [
                'user_id' => $request->user_id,
                'company_name' => $request->company_name,
                'jpy' => $request->jpy,
                'vnd' => $request->vnd,
                'usd' => $request->usd,
                'exchange_rate_id' => $request->exchange_rate_id,
                'payment_date' => $request->payment_date,
            ]
        );
        if ($payment_order) {
            return response()->json([
                'success' => true,
                'message' => 'Create payment_order successfully',
                'payment_order' => $payment_order
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Server error'
        ], 500);

    }
    public function update_payment_order(Request $request)
    {
            $validated = Validator::make($request->all(), [
                'user_id' => 'required',
                'company_name' => 'required',
                'jpy' => 'required',
                'vnd' => 'required',
                'usd' => 'required',
                'exchange_rate_id' => 'required',
                'payment_date' => 'required',
            ]);
            if ($validated->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validated->errors()
                ], 400);
            }
            $new_payment_order = request()->all();
            $payment_order_id = request()->route('id');
            $payment_order = PaymentOrder::find($payment_order_id);
            if ($payment_order){
                $payment_order->update($new_payment_order);
                return response()->json([
                    'success' => true,
                    'message' => 'Update payment_order successfully',
                    'payment_order' => $payment_order
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'payment_order not found'
                ], 404);
            }
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        
        
    }

    public function delete_payment_order(Request $request)
    {
        $payment_order_id = request()->route('id');
        $payment_order = PaymentOrder::find($payment_order_id);
        if ($payment_order) {
            $payment_order->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete payment_order successfully',
                'payment_order' => $payment_order
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'payment_order not found'
            ], 404);
        }
        return response()->json([
            'success' => false,
            'message' => 'Server error'
        ], 500);
    }
}
