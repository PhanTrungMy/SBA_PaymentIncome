<?php

namespace App\Http\Controllers;

use App\Models\Outsourcing;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OutsourcingController extends Controller
{
    public function show_all_outsourcing(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 5);
            $page = $request->query('page', 1);
            $outsourcing = Outsourcing::whereNull('deleted_at')->orderBy('created_at', 'desc');
            if ($request->has('month')) {
                $month = $request->query('month');
                $outsourcing = $outsourcing->whereMonth('outsourced_date', $month);
            }
            if ($request->has('year')) {
                $year = $request->query('year');
                $outsourcing = $outsourcing->whereYear('outsourced_date', $year);
            }
            $outsourcing = $outsourcing->paginate($perPage, ['*'], 'page', $page);
            $outsourcingItems = $outsourcing->items();
            foreach ($outsourcingItems as $item) {
                $exchangeRate = ExchangeRate::find($item->exchange_rate_id);
                $item->jpy = $item->vnd / $exchangeRate->jpy;
                $item->usd = $item->vnd / $exchangeRate->usd;
            }
            return response()->json([
                'success' => true,
                'message' => 'Get all outsourcing successfully',
                'total_result' => $outsourcing->total(),
                'pagination' => [
                    'per_page' => $outsourcing->perPage(),
                    'current_page' => $outsourcing->currentPage(),
                    'total_pages' => $outsourcing->lastPage()
                ],
                'outsourcing' => $outsourcingItems
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function show_id_outsourcing($id)
    {
        try {
            $outsourcing = Outsourcing::find($id);
            if (!$outsourcing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outsourcing not found'
                ], 404);
            }
            $exchangeRate = ExchangeRate::find($outsourcing->exchange_rate_id);
            $outsourcing->jpy = $outsourcing->vnd / $exchangeRate->jpy;
            $outsourcing->usd = $outsourcing->vnd / $exchangeRate->usd;
            return response()->json([
                'success' => true,
                'message' => 'Get outsourcing successfully',
                'outsourcing' => $outsourcing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function create_outsourcing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'company_name' => 'required|string',
                'vnd' => 'required',
                'exchange_rate_id' => 'required|integer',
                'outsourced_project' => 'required|string',
                'outsourced_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag()->toArray();
                $firstErrorField = array_key_first($errors);
                $readableFieldName = str_replace('_', ' ', $firstErrorField);
                return response()->json([
                    'success' => false,
                    'message' => $readableFieldName . ' is required.'
                ], 400);
            }

            $outsourcing = new Outsourcing();
            $outsourcing->fill($request->all());
            $outsourcing->save();

            return response()->json([
                'success' => true,
                'message' => 'Create outsourcing successfully',
                'outsourcing' => $outsourcing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function update_outsourcing(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'company_name' => 'required|string',
                'vnd' => 'required',
                'exchange_rate_id' => 'required|integer',
                'outsourced_project' => 'required|string',
                'outsourced_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag()->toArray();
                $firstErrorField = array_key_first($errors);
                $readableFieldName = str_replace('_', ' ', $firstErrorField);
                return response()->json([
                    'success' => false,
                    'message' => $readableFieldName . ' is required.'
                ], 400);
            }

            $outsourcing = Outsourcing::find($id);
            if (!$outsourcing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outsourcing not found'
                ], 404);
            }

            $outsourcing->fill($request->all());
            $outsourcing->save();

            return response()->json([
                'success' => true,
                'message' => 'Update outsourcing successfully',
                'outsourcing' => $outsourcing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function delete_outsourcing(Request $request)
    {
        $ids = $request->input('id');

        if (!$ids || !is_array($ids)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Outsourcing not found'
                ],
                400
            );
        }

        try {
            $deleted = [];
            foreach ($ids as $id) {
                $outsourcing = Outsourcing::find($id);
                if (!$outsourcing) {
                    continue;
                }

                $deletedOutsourcing = clone $outsourcing;
                $outsourcing->delete();

                $deleted[] = [
                    'id' => $deletedOutsourcing->id,
                    'user_id' => $deletedOutsourcing->user_id,
                    'company_name' => $deletedOutsourcing->company_name,
                    'jpy' => $deletedOutsourcing->jpy,
                    'usd' => $deletedOutsourcing->usd,
                    'vnd' => $deletedOutsourcing->vnd,
                    'exchange_rate_id' => $deletedOutsourcing->exchange_rate_id,
                    'outsourced_project' => $deletedOutsourcing->outsourced_project,
                    'outsourced_date' => $deletedOutsourcing->outsourced_date,
                    'created_at' => $deletedOutsourcing->created_at->toDateTimeString(),
                    'updated_at' => $deletedOutsourcing->updated_at->toDateTimeString(),
                    'deleted_at' => now()->toDateTimeString()
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Deleted outsourcings',
                'outsourcings' => $deleted
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
