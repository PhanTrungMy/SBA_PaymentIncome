<?php

namespace App\Http\Controllers;

use App\Models\BalanceSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceSheetController extends Controller
{
    public function balance_create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'month_year' => 'required|date_format:Y-m',
            'balances' => 'required|array',
            'balances.*.amount' => 'required|numeric',
            'balances.*.category_id' => 'required|integer|exists:categories,id'
        ], [
            'month_year.date_format' => 'month_year is invalid',
        ]);

        if ($validator->fails()) {
            $errorMessage = collect($validator->errors())->map(function ($errors, $field) {
                return str_replace(
                    'balances.0.',
                    '',
                    $errors[0]
                );
            })->first();

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 400);
        }

        foreach ($request->balances as $balance) {
            BalanceSheet::create([
                'bs_month_year' => $request->month_year,
                'amount' => $balance['amount'],
                'category_id' => $balance['category_id']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Create balance_sheets successfully'
        ], 200);
    }


    public function balance_get(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'month_year' => 'required|date_format:Y-m'
            ]);

            $balanceSheets = BalanceSheet::where('bs_month_year', $validatedData['month_year'])
                ->whereNull('deleted_at')
                ->get();

            if ($balanceSheets->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Balance Sheets not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Get balance_sheets successfully',
                'balance_sheet' => $balanceSheets
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('month_year')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Balance Sheets not found'
                ], 404);
            }
        }
    }
}
