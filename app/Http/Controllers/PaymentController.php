<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function create_payments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cost' => 'required',
            'currency_type' => 'required',
            'note' => 'required',
            'invoice' => 'required',
            'pay' => 'required',
            'category_id' => 'required',
            'exchange_rate_id' => 'required',
            'payment_date' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $payment = Payment::create(
            [
                'id' => $request->id,
                'user_id' => $request->user_id,
                'name' => $request->name,
                'cost' => $request->cost,
                'currency_type' => $request->currency_type,
                'note' => $request->note,
                'invoice' => $request->invoice,
                'pay' => $request->pay,
                'category_id' => $request->category_id,
                'exchange_rate_id' => $request->exchange_rate_id,
                'payment_date' => $request->payment_date,
            ]
        );
        Category::where('id', $request->category_id)->increment('payment_count');
        $payment->save();
        if ($payment) {
            return response()->json([
                'success' => true,
                'message' => 'Create payment successfully',
                'payment' => $payment
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Create payment failed',
            ], 404);
        }
        return response()->json([
            'success' => false,
            'message' => 'Server error'
        ], 500);
    }
    public function paymentsId(Request $request, $id)
    {
        $payment = Payment::with('category')->find($id);
        if ($payment) {
            return response()->json([
                "success" => true,
                "message" => "Get payment successfully",
                "payment" => $payment
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Payment not found"
            ], 404);
        }
        return response()->json([
            "success" => false,
            "message" => "Server error"
        ], 500);
    }
    public function update_payments(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cost' => 'required',
            'currency_type' => 'required',
            'note' => 'required',
            'invoice' => 'required',
            'pay' => 'required',
            'category_id' => 'required',
            'exchange_rate_id' => 'required',
            'payment_date' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $payment = Payment::findOrFail($id);
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Not found payment',
            ], 404);
        }
        $oldCategoryId = $payment->category_id;
        $payment->update($request->all());
        if ($oldCategoryId != $request->category_id) {
            Category::where('id', $oldCategoryId)->decrement('payment_count');
            Category::where('id', $request->category_id)->increment('payment_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'Update payment successfully',
            'payment' => $payment
        ], 200);
    }
    public function delete_payments(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Not found payment',
            ], 404);
        }
        $payment->deleted_at = now();
        $payment->save();
        Category::where('id', $payment->category_id)->decrement('payment_count');
        return response()->json([
            'success' => true,
            'message' => 'Delete payment successfully',
            'payment' => $payment
        ], 200);
    }
}
