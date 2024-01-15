<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExchangeRate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnalyticController extends Controller
{
    public function category_analytics(Request $request){

        $validated = Validator::make($request->all(), [
            'date' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validated->errors()
            ], 400);
        }
      
        $found_id = ExchangeRate::where('exchange_rate_month', $request->date)->first();
        if (!$found_id) {
            return response()->json([
                'success' => false,
                'message' => 'Exchange rate not found',
            ], 404);
        }
        $categories = DB::select('SELECT 
          categories.id,
          categories.name,
          categories.payment_count,
          categories.group_id,
          categories.created_at,
          categories.updated_at,
          categories.deleted_at,
          SUM(CASE 
            WHEN currency_type = "jpy" THEN cost
            WHEN currency_type = "usd" THEN cost * er.usd
            ELSE NULL
          END) AS cost
          FROM payments
          INNER JOIN categories ON payments.category_id = categories.id
          INNER JOIN exchange_rates er ON payments.exchange_rate_id = er.id
          WHERE exchange_rate_id = ?
          GROUP BY categories.id, categories.name
          ORDER BY cost DESC', [$found_id->id]);

        if ($categories) {
          return response()->json([
            'success' => true,
            'message' => 'Category analytics retrieved successfully.',
            'data' => $categories
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Category analytics not found.',
          ], 404);
        }
        return response()->json([
          'success' => false,
          'message' => 'Server error'
        ], 500);
    }
}
