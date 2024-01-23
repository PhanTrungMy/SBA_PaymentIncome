<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function get_all_payments(Request $request)
    {
        try {
            $totalUSD = 0;
            $totalCost = 0;
            $totalJPY = 0;
            $resultPayments = [];
            $perPage = $request->query('per_page') ?? 10;
            $perPage = in_array($perPage, [10, 20]) ? $perPage : 10;
            $month = $request["month"];
            $year = $request["year"];
            $query = Payment::with('Category')
                ->whereNull('deleted_at')->orderBy('created_at', 'desc');

            if ($month && $year) {
                $query->whereMonth('payment_date', $month)
                    ->whereYear('payment_date', $year);
            }
            $totalCost = $query->sum('cost');
            $totalUSD = $query->get()->reduce(function ($carry, $payment) {
                return $carry + $payment->cost / $payment->exchange_rate->usd;
            }, 0);
            $totalJPY = $query->get()->reduce(function ($carry, $payment) {
                return $carry + $payment->cost / $payment->exchange_rate->jpy;
            }, 0);
            $payments = $query->paginate($perPage);
            foreach ($payments as $payment) {
                $jpy = $payment->cost / $payment->exchange_rate->jpy;
                $usd = $payment->cost / $payment->exchange_rate->usd;
                $resultPayments[] = [
                    "id" => $payment->id,
                    'user_id' => $payment->user_id,
                    'name' => $payment->name,
                    'cost' => $payment->cost,
                    'jpy' => $jpy,
                    'usd' => $usd,
                    'currency_type' => $payment->currency_type,
                    'note' => $payment->note,
                    'invoice' => $payment->invoice,
                    'pay' => $payment->pay,
                    'exchange_rate_id' => $payment->exchange_rate_id,
                    'payment_date' => $payment->payment_date,
                    'created_at' => $payment->created_at,
                    'category' =>  [
                        'id' => $payment->category->id,
                        'name' => $payment->category->name,
                        'payment_count' => $payment->category->payment_count,
                    ],

                ];
            }
            $pagination = [
                'per_page' => $payments->perPage(),
                'current_page' => $payments->currentPage(),
                'total_pages' => $payments->lastPage(),
            ];
            if (empty($resultPayments)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No data found',
                    'total_result' => 0,
                    'pagination' => null,
                    'payments' => [],
                    'category' => null,
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => 'Get all payments successfully',
                'total_usd' => $totalUSD,
                'total_cost' => $totalCost,
                'total_jpy' => $totalJPY,
                'total_result' => $payments->total(),
                'pagination' => $pagination,
                'payments' => $resultPayments,

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function create_payments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'cost' => 'required',
                'currency_type' => 'required',
                'pay' => 'required',
                'category_id' => 'required',
                'exchange_rate_id' => 'required',
                'payment_date' => 'required',

            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = [];
                foreach ($errors->all() as $error) {
                    $errorMessage[] = $error;
                }
                return response()->json([
                    'success' => false,
                    'message' => implode(', ', $errorMessage)
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
            return response()->json([
                'success' => true,
                'message' => 'Create new payment successfully',
                'payment' => $payment
            ], 200);
            Category::where('id', $request->category_id)->increment('payment_count');
            $payment->save();
            if ($payment) {
                return response()->json([
                    'success' => true,
                    'message' => 'Create new payment successfully',
                    'payment' => $payment
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Fields are not proper',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function paymentsId($id)
    {
        try {
            $payments = Payment::with('category')->find($id);
            return response()->json([
                'success' => true,
                'message' => 'Get payment successfully',
                'payments' => $payments,

            ], 200);
        } catch (\Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }


    public function update_payments(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'cost' => 'required',
                'currency_type' => 'required',
                'pay' => 'required',
                'category_id' => 'required',
                'exchange_rate_id' => 'required',
                'payment_date' => 'required',

            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = [];
                foreach ($errors->all() as $error) {
                    $errorMessage[] = $error;
                }
                return response()->json([
                    'success' => false,
                    'message' => implode(', ', $errorMessage)
                ], 400);
            }
            $payment = Payment::findOrFail($id);
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function delete_payments(Request $request)
    {
        try {
            $ids = $request->input('id');
            $payments = Payment::whereIn('id', $ids)->get();
            
            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No payments found',
                ], 404);
            }
            
            Payment::whereIn('id', $ids)->update(['deleted_at' => now()]);
            
            $categoryIds = $payments->pluck('category_id')->unique();
            Category::whereIn('id', $categoryIds)->decrement('payment_count', count($ids));
            
            return response()->json([
                'success' => true,
                'message' => 'Payments deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
