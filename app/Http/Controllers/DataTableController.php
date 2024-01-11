<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataTableController extends Controller
{
    public function get_data_table(Request $request){
        $year = $request->year;
        $reportType = $request->report_type;
    
        $payments =  DB::select("
            SELECT
                DATE_FORMAT(payments.payment_date, '%Y-%m') AS payment_date,
                categories.name as category_name,
                groups.name as group_name,
                SUM(payments.cost) AS total_cost
            FROM payments
            JOIN categories ON payments.category_id = categories.id
            JOIN groups ON categories.group_id = groups.id
            WHERE YEAR(payments.payment_date) = :year
            AND groups.report_type = :reportType
            GROUP BY payment_date, categories.name, groups.name
        ", ['year' => $year, 'reportType' => $reportType]);
        if($payments){
            return response()->json([
                'success' => true,
                'message' => 'Data table retrieved successfully.',
                'data' => $payments
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data table not found.',
            ], 404);
        }
        return response()->json([
            'success' => false,
            'message' => 'Server error'
        ], 500);
    }
}