<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    function Get_all_order(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 10);
            $perPageOptions = [10, 20];
            $Page = $request["Page"];

            if (!in_array($perPage, $perPageOptions)) {
                $perPage = 10;
            }
            $currentPage = request()->query('page', $Page);

            $data_1 = Order::whereNull('deleted_at')
                ->orderBy('id', 'asc')
                ->paginate($perPage, ['id', 'user_id', 'company_name', 'jpy', 'usd', 'vnd', 'exchange_rate_id', 'order_date', 'created_at', 'updated_at', 'deleted_at'], 'page', $currentPage);
            $Get_All_paginator = DB::table("orders")->where("deleted_at", null)->paginate($perPage);

            if ($Get_All_paginator->count() > 0) {
                
                return response()->json([
                    "success" => true,
                    "message" => "Get all order successfully",
                    "total_results" => $Get_All_paginator->total(),
                    "pagination" => [
                        "per_page" => $perPage,
                        "current_page" => $currentPage,
                        "total_pages" => $Get_All_paginator->lastPage(),
                    ],
                    "orders" => $data_1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Internal server error"
            ], 500);
        }

    }

    function Get_Order_By_ID(Request $request, $id)
    {
        try {
            // $ID_Order = $request["id"];
            $OrderByID = DB::table("orders")->where("id", $id)->get();
            if ($OrderByID->count() > 0) {
                return response()->json([
                    "success" => true,
                    "message" => "Get order successfully",
                    "order" => $OrderByID,
                ], 200);
            }
            if ($OrderByID->count() == 0) {
                return response()->json([
                    "success" => false,
                    "message" => "Order not found"
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Internal server error"
            ], 500);
        }
    }

    function Create_Order(Request $request)
    {
        $user_id = $request["user_id"];
        $exchange_rate_id = $request["exchange_rate_id"];

        $order_create_2 = Validator::make($request->all(), [
            'company_name' => "string",
            'vnd' => 'nullable|float',
            'order_date' => "nullable|date"
        ], [
            'company_name.string' => "company_name is string",
            "vnd.float" => "vnd furigana is float",
            "order_date.date" => "order date is date"
        ]);

        $order_create = Validator::make($request->all(), [
            'company_name' => "required",
            'order_date' => "required"
        ], [
            'company_name.required' => "company_name is required",
            "order_date.required" => "order date is required"
        ]);

        $check_user_id = DB::table("users")->where("id", $user_id)->get();
        $check_exchange_rate_id = DB::table("exchange_rates")->where("id", $exchange_rate_id)->get();
        $jpn = $request["vnd"] / ($check_exchange_rate_id->first()->jpy);
        $usd = $request["vnd"] / ($check_exchange_rate_id->first()->usd);

        if ($check_user_id != null) {
            if ($check_exchange_rate_id != null) {
                if ($order_create->fails()) {
                    return response()->json([
                        "success"=>false,
                        "message"=> $order_create->errors(),
                    ], 400);
                }if ($order_create_2->fails()){
                        return response()->json([
                            "success"=>false,
                            "message"=>"Internal server error"
                    ], 500);
                } else {
                    $originalDate = $request->input('order_date');
                    $formattedDate = Carbon::createFromFormat('d-m-Y', $originalDate)->format('Y-m-d');
                    $request->merge(['order_date' => $formattedDate]);
                    $save = DB::table("orders")->insertGetId([
                        "user_id" => $user_id,
                        "company_name" => $request["company_name"],
                        "vnd" => $request["vnd"],
                        "order_date" => $request["order_date"],
                        "exchange_rate_id" => $request["exchange_rate_id"],
                        "jpy"=> $jpn,
                        "usd"=> $usd,
                        "created_at" => now(),
                        "updated_at"=>now()
                    ]);
                    $get_save = DB::table("orders")->find($save);
                    return response()->json([
                        "success" => true,
                        "message" => "Create order successfully",
                        "order" => $get_save,
                    ], 200);
                }
            }
        }
    }
    function Update_Order(Request $request, $id){
        $user_id = $request["user_id"];
        $exchange_rate_id = $request["exchange_rate_id"];

        $order_create_2 = Validator::make($request->all(), [
            'company_name' => "string",
            'vnd' => 'nullable|float',
            'order_date' => "nullable|date"
        ], [
            'company_name.string' => "company_name is string",
            "vnd.float" => "vnd furigana is float",
            "order_date.date" => "order date is date"
        ]);

        $order_create = Validator::make($request->all(), [
            'company_name' => "required",
            'order_date' => "required"
        ], [
            'company_name.required' => "company_name is required",
            "order_date.required" => "order date is required"
        ]);
        $check_user_id = DB::table("users")->where("id", $user_id)->get();
        $check_exchange_rate_id = DB::table("exchange_rates")->where("id", $exchange_rate_id)->get();
        $jpn = $request["vnd"] / ($check_exchange_rate_id->first()->jpy);
        $usd = $request["vnd"] / ($check_exchange_rate_id->first()->usd);

        if ($check_user_id != null) {
            if ($check_exchange_rate_id != null) {
                if ($order_create->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => $order_create->errors(),
                    ], 400);
                }
                if ($order_create_2->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => "Internal server error"
                    ], 500);
                }
                $check_order = DB::table("orders")->where("id", $id)->first();
                if ($check_order == null){
                    return response()->json([
                        "success"=>false,
                        "message"=>"Order not found"
                    ], 404);
                }
                else {
                    $originalDate = $request->input('order_date');
                    $formattedDate = Carbon::createFromFormat('d-m-Y', $originalDate)->format('Y-m-d');
                    $request->merge(['order_date' => $formattedDate]);
                    $check_order = DB::table("orders")->where("id", $id);
                    $check_order->update([
                        "user_id" => $user_id,
                        "company_name" => $request["company_name"],
                        "vnd" => $request["vnd"],
                        "order_date" => $request["order_date"],
                        "exchange_rate_id" => $request["exchange_rate_id"],
                        "jpy" => $jpn,
                        "usd" => $usd,
                        "updated_at" => now()
                    ]);
                    $get_update = DB::table("orders")->where("id", $id)->first();
                    return response()->json([
                        "success" => true,
                        "message" => "Update order successfully",
                        "order" => $get_update,
                    ], 200);
                }
            }
        }
    }
    function Delete_Order(Request $request, $id){
        $delete_order_check = DB::table("orders")->where("id", $id)->first();
        $delete_order = DB::table("orders")->where("id", $id);
        if($delete_order_check!=null){
            $delete_order->update([
                "deleted_at"=>now()
            ]);
            return response()->json([
                "success" => true,
                "message" => "Delete order successfully",
                "order" => DB::table("orders")->where("id", $id)->first()
            ], 200);
        }
        if ($delete_order_check==null){
            return response()->json([
                "success"=>false,
                "message"=>"Order not found"
            ], 404);
        }
        $check_fails = DB::table("orders")->where("id", $id)->first();
        if($check_fails == null){
            return response()->json([
                "success"=>"fails",
                "message"=>"Internal server error"
            ], 500);
        }
    }
}
