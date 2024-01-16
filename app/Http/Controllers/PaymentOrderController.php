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
        try {
            $resultPayments = [];
            $perPage = $request->query('per_page') ?? 5;
            $perPage = in_array($perPage, [5, 10, 20]) ? $perPage : 5;
            $query = PaymentOrder::where('deleted_at', null);
            $payment_order = $query->paginate($perPage);
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
            $pagination = [
                'per_page' => $payment_order->perPage(),
                'current_page' => $payment_order->currentPage(),
                'total_pages' => $payment_order->lastPage(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get all payment_orders successfully',
                'total_result' => $payment_order->total(),
                'pagination' => $pagination,
                'payment_orders' => $resultPayments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function PaymentOrderId(Request $request, $id)
    {
        $id = $request->route('id');
        $query = 'SELECT * FROM payment_orders WHERE id = ? AND deleted_at IS NULL ORDER BY id ASC';
        $payment_order = DB::select($query, [$id]);
        if ($payment_order == null) {
            return response()->json([
                "success" => false,
                "message" => "Payment_orders not found",
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
        try {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'vnd' => 'required|numeric',
                'payment_date' => 'required',
                'exchange_rate_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('company_name')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Company_name is required'
                    ], 400);
                }
                if ($errors->has('vnd')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vnd is required'
                    ], 400);
                }
                if ($errors->has('payment_date')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment_date is required'
                    ], 400);
                }
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
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                    'deleted_at' => $request->deleted_at,
                ]
            );
            $payment_order->save();
            if ($payment_order) {
                return response()->json([
                    'success' => true,
                    'message' => 'Create payment_orders successfully',
                    'payment_order' => $payment_order
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Create payment_order failed',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function update_payment_order(Request $request)
    {
        try{
        $validated = Validator::make($request->all(), [
            'company_name' => 'required|string',
            'vnd' => 'required|numeric',
            'payment_date' => 'required',
            'exchange_rate_id' => 'required',
        ]);
        if ($validated->fails()) {
            $errors = $validated->errors();
            $errorMessage = [];
            foreach ($errors->all() as $error) {
                $errorMessage[] = $error;
            }
            return response()->json([
                'success' => false,
                'message' => implode(', ', $errorMessage)
            ], 400);
        }
        $new_payment_order = request()->all();
        $payment_order_id = request()->route('id');
        $payment_order = PaymentOrder::findOrFail($payment_order_id);
        $payment_order->save();
        if ($payment_order) {
            $payment_order->update($new_payment_order);
            return response()->json([
                'success' => true,
                'message' => 'Update payment_order successfully',
                'payment_order' => $payment_order
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'payment_order not found'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Internal server error'
        ], 500);
    }
}

    public function delete_payment_order(Request $request)
    {
        $payment_order_id = request()->route('id');
        $payment_order = PaymentOrder::findOrFail($payment_order_id);
        if ($payment_order) {
            $payment_order->deleted_at = now();
            $payment_order->save();
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
            'message' => 'Internal server error'
        ], 500);
    }
}
