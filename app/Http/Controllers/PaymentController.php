<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function create_payments(Request $request){
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
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'deleted_at' => $request->deleted_at,
        ]
    );
    $payment->save();
    if ($payment) {
        return response()->json([
            'success' => true,
            'message' => 'Create payment successfully',
            'payment' => $payment
        ], 200);
    }
    else{
        return response()->json([
            'success' => false,
            'message' => 'Create payment failed',
        ],404);
    }
    return response()->json([
        'success' => false,
        'message' => 'Server error'
    ], 500);
    }

}

