<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Carbon;

class ExchangeRateController extends Controller
{
        function ExchangeRateByMonthYear(Request $request)
        {
            $month = $request["month"];
            $year = $request["year"];
            $month_and_year = $year . "-" . $month;
            $check_by_month_or_year = DB::table("exchange_rates");
            if ($month != null && $year != null) {
                $check_by_month_or_year->where("exchange_rate_month", "LIKE", "%$month_and_year%");
            }
            return response()->json([
                "success" => True,
                "message" => "get exchange rates successfully",
                "data" => $check_by_month_or_year->get()
            ], 200);
                return response()->json([
                    "success" => false,
                    "message" => "Internal server error"
                ], 500);     
        }
    
    function CreateExchangeRate(Request $request)
    {
        $exchangeDate = $request["exchangeDate"];
        $formattedDate = Carbon::createFromFormat('m-Y', $exchangeDate)->format('Y-m');
        $jpn = $request["jpn"];
        $usd = $request["usd"];
        $exchangeRate = Validator::make($request->all(), [
            "exchangeDate" => "nullable|string",
            "jpn" => "required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "usd" => "required|numeric|regex:/^\d+(\.\d{1,2})?$/"
        ], [
            "jpn.required" => "jpn is required",
            "jpn.numeric" => "jpn is numeric",
            "usd.numeric" => "usd is numeric",
            "jpn.regex" => "jpn is decimal",
            "usd.regex" => "usd is decimal"
        ]);
        if ($exchangeRate->fails()) {
            return response()->json([
                "success" => true,
                "message" => "Fields are not proper"
            ], 400);
        } else {
            try {
                DB::table("exchange_rates")->insert([
                    "jpn" => $jpn,
                    "usd" => $usd,
                    "exchange_rate_month" => $formattedDate
                ]);
                return response()->json([
                    "success" => true,
                    "message" => "create exchange rate successfully",
                    "exchangeDate" => $exchangeDate,
                    "jpn" => $jpn,
                    "usd" => $usd,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => "Internal server error"
                ], 500);
            }
        }
    }
    function UpdateChangeRate(Request $request, $id)
    {
        $check_id = DB::table("exchange_rates")->where("id", $id)->first();
        $exchangeDate = $request["exchangeDate"];
        $formattedDate = Carbon::createFromFormat('m-Y', $exchangeDate)->format('Y-m');
        $jpn = $request["jpn"];
        $usd = $request["usd"];
        $exchangeRate = Validator::make($request->all(), [
            "exchangeDate" => "nullable|string",
            "jpn" => "required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "usd" => "required|numeric|regex:/^\d+(\.\d{1,2})?$/"
        ], [
            "jpn.required" => "jpn is required",
            "jpn.numeric" => "jpn is numeric",
            "usd.numeric" => "usd is numeric",
            "jpn.regex" => "jpn is decimal",
            "usd.regex" => "usd is decimal"
        ]);
        if ($exchangeRate->fails()) {
            return response()->json([
                "success" => true,
                "message" => "Fields are not proper"
            ], 400);
        } else {
            try {
                if ($check_id != null) {
                    DB::table("exchange_rates")->update([
                        "jpn" => $jpn,
                        "usd" => $usd,
                        "exchange_rate_month" => $formattedDate
                    ]);
                    return response()->json([
                        "success" => true,
                        "message" => "update exchange rate successfully",
                        "exchangeDate" => $exchangeDate,
                        "jpn" => $jpn,
                        "usd" => $usd,
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Internal server error"
                    ], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => "Internal server error"
                ], 500);
            }
        }
    }
}
