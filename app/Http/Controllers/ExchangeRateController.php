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

        $firstChar = substr($month, 0, 2);
        $SecondChar  = substr($month, 0, 2);

        $check = DB::table("exchange_rates")->get();

        $check_by_month_or_year = DB::table("exchange_rates");

        if ($month == null && $year != null){
            $check_by_month_or_year->where("exchange_rate_month","LIKE", "%$year-%");
        }
        if ($month != null && strlen($month) == 2 && $firstChar != 0 && $year == null) {
            $check_by_month_or_year->where("exchange_rate_month", "LIKE", "%-$month%");
        }
        if ($month != null && strlen($month) == 1 && $firstChar != 0 && $year == null) {
            $check_by_month_or_year->where("exchange_rate_month", "LIKE", "%-0$month%");
        }
        if ($month != null && $year != null) {
            if (strlen($month) == 2 && $firstChar != 0) {
                $check_by_month_or_year->where("exchange_rate_month", "LIKE", "%-$month%")->where("exchange_rate_month", "LIKE", "%$year-%");
            }
            if (strlen($month) == 1 && $firstChar != 0) {
                $check_by_month_or_year->where("exchange_rate_month", "LIKE", "%-0$month%")->where("exchange_rate_month", "LIKE", "%$year-%");
            }
        }
        return response()->json([
            "success" => True,
            "message" => "get exchange rates successfully",
            "data" => $check_by_month_or_year->get()
        ], 200);

        if ($month == null && $year == null){
            return response()->json([
                "success" => True,
                "message" => "get exchange rates successfully",
                "data" => $check
            ], 200);
        } 
        else {
            return response()->json([
                "success" => false,
                "message" => "Internal server error"
            ], 500);
        }
    }
    function CreateExchangeRate(Request $request)
    {
        $exchangeDate = $request["exchangeDate"];
        $formattedDate = Carbon::createFromFormat('m-Y', $exchangeDate)->format('Y-m');
        $jpy = $request["jpy"];
        $usd = $request["usd"];
        $exchangeRate = Validator::make($request->all(), [
            "exchangeDate" => "nullable|string",
            "jpy" => "required|numeric",
            "usd" => "required|numeric"
        ], [
            "jpy.required" => "jpy is required",
            "jpy.numeric" => "jpy is numeric",
            "usd.numeric" => "usd is numeric",
            "usd.required" => "usd is required"
        ]);
        if ($exchangeRate->fails()) {
            return response()->json([
                "success" => true,
                "message" => "Fields are not proper"
            ], 400);
        } else {
            try {
                DB::table("exchange_rates")->insert([
                    "jpy" => $jpy,
                    "usd" => $usd,
                    "exchange_rate_month" => $formattedDate
                ]);
                return response()->json([
                    "success" => true,
                    "message" => "create exchange rate successfully",
                    "exchangeDate" => $exchangeDate,
                    "jpy" => $jpy,
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
        $jpy = $request["jpy"];
        $usd = $request["usd"];
        $exchangeRate = Validator::make($request->all(), [
            "exchangeDate" => "nullable|string",
            "jpy" => "required|numeric",
            "usd" => "required|numeric"
        ], [
            "jpy.required" => "jpy is required",
            "jpy.numeric" => "jpy is numeric",
            "usd.numeric" => "usd is numeric",
            "usd.required" => "usd is required"
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
                        "jpy" => $jpy,
                        "usd" => $usd,
                        "exchange_rate_month" => $formattedDate
                    ]);
                    return response()->json([
                        "success" => true,
                        "message" => "update exchange rate successfully",
                        "exchangeDate" => $exchangeDate,
                        "jpy" => $jpy,
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
