<?php

namespace App\Http\Controllers;

use App\Models\Outsourcing;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutsourcingController extends Controller
{
    public function show_all_outsourcing(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 5);
            $page = $request->query('page', 1);

            $outsourcing = Outsourcing::whereNull('deleted_at')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'message' => 'Get all outsourcing successfully',
                'total_result' => $outsourcing->total(),
                'pagination' => [
                    'per_page' => $outsourcing->perPage(),
                    'current_page' => $outsourcing->currentPage(),
                    'total_pages' => $outsourcing->lastPage()
                ],
                'outsourcing' => $outsourcing->items()
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
                'jpy' => 'nullable|float',
                'usd' => 'nullable|float',
                'vnd' => 'required|float',
                'exchange_rate_id' => 'required|integer',
                'outsourced_project' => 'required|string',
                'outsourced_date' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag()->toArray();
                $firstErrorField = array_key_first($errors); // Lấy tên trường đầu tiên có lỗi

                // Chuyển đổi tên trường từ snake_case thành text bình thường, không viết hoa
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
                'jpy' => 'nullable|float',
                'usd' => 'nullable|float',
                'vnd' => 'required|float',
                'exchange_rate_id' => 'required|integer',
                'outsourced_project' => 'required|string',
                'outsourced_date' => 'nullable|date',
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

    public function delete_outsourcing($id)
    {
        try {
            $outsourcing = Outsourcing::find($id);

            if (!$outsourcing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outsourcing not found'
                ], 404);
            }

            $deletedOutsourcing = clone $outsourcing;
            $outsourcing->delete();

            return response()->json([
                'success' => true,
                'message' => 'Update outsourcing successfully',
                'outsourcing' => [
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
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
