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
    public function category_analytics(Request $request)
    {
        try{
        $validated = Validator::make($request->all(), [
            'date' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validated->errors()
            ], 400);
        }

        $date = $request->input('date');
        $found_id = DB::table('payments')
                ->join('categories', 'payments.category_id', '=', 'categories.id')
                ->join('exchange_rates', 'payments.exchange_rate_id', '=', 'exchange_rates.id')
                ->selectRaw('categories.id, categories.name, categories.payment_count, categories.group_id, categories.created_at, categories.updated_at, categories.deleted_at, SUM(payments.cost) AS cost_vnd, SUM(payments.cost / exchange_rates.usd) AS cost_usd, SUM(payments.cost / exchange_rates.jpy) AS cost_jpy')
                ->where('payments.payment_date', 'like', '%' . $date . '%')
                ->groupBy('categories.id', 'categories.name', 'categories.payment_count', 'categories.group_id', 'categories.created_at', 'categories.updated_at', 'categories.deleted_at')
                ->whereNull('payments.deleted_at')
                ->get();
        $total_cost_jpy = $found_id->sum('cost_jpy');
        $total_cost_vnd = $found_id->sum('cost_vnd');
        $total_cost_usd = $found_id->sum('cost_usd');
        if (empty($found_id)) {
            return response()->json([
                'success' => true,
                'message' => 'No data found',
                'categories' => [
                    'total_cost_jpy' => 0,
                    'total_cost_vnd' => 0,
                    'total_cost_usd' => 0,
                    'category_analytics' => []
                ]
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => 'Get categories successfully',
            'categories' => [
                'total_cost_jpy' => $total_cost_jpy,
                'total_cost_vnd' => $total_cost_vnd,
                'total_cost_usd' => $total_cost_usd,
                'category_analytics' => $found_id
                
            ]
        ], 200);
    }
    catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Internal server error',
        ], 500);
    }
    }
}



   
