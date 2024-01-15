<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function catogory_show_all(Request $request)
    {
        try {
            // Lấy dữ liệu từ request body
            $perPage = $request->input('per_page', 10);
            $name = $request->input('name');
            $groupId = $request->input('group_id');
            $reportType = $request->input('report_type');

            $query = Category::with('group')->whereNull('deleted_at');

            if (!empty($name)) {
                $query->where('name', 'like', "%{$name}%");
            }

            if (!empty($groupId)) {
                $query->where('group_id', $groupId);
            }

            if (!empty($reportType)) {
                $query->whereHas('group', function ($q) use ($reportType) {
                    $q->where('report_type', $reportType);
                });
            }

            $categories = $query->paginate($perPage);

            $transformedCategories = $categories->getCollection()->transform(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'group_id' => $category->group_id,
                    'payment_count' => $category->payment_count,
                    'created_at' => $category->created_at->toDateTimeString(),
                    'updated_at' => $category->updated_at->toDateTimeString(),
                    'deleted_at' => $category->deleted_at ? $category->deleted_at->toDateTimeString() : null,
                    'report_type' => $category->group ? $category->group->report_type : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Get all categories successfully',
                'total_result' => $categories->total(),
                'pagination' => [
                    'per_page' => $categories->perPage(),
                    'current_page' => $categories->currentPage(),
                    'total_pages' => $categories->lastPage(),
                ],
                'categories' => $transformedCategories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }


    public function catogory_show_id($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    "success" => false,
                    "message" => "Category not found"
                ], 400);
            }
            if (!is_null($category->deleted_at)) {
                return response()->json([
                    "success" => false,
                    "message" => "Category not found"
                ], 400);
            }
            return response()->json([
                "success" => true,
                "message" => "Get category successfully",
                "category" => $category
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function catogory_create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'group_id' => 'required|integer|exists:groups,id'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('name')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Field name is required'
                    ], 400);
                }

                if ($errors->has('group_id')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Group not found'
                    ], 404);
                }
            }

            $category = new Category;
            $category->name = $request->name;
            $category->group_id = $request->group_id;
            $category->payment_count = 0;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Create new category successfully',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'group_id' => $category->group_id,
                    'payment_count' => $category->payment_count,
                    'created_at' => $category->created_at->toDateTimeString(),
                    'updated_at' => $category->updated_at->toDateTimeString(),
                    'deleted_at' => $category->deleted_at
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function catogory_update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'group_id' => 'required|integer|exists:groups,id'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();

                if ($errors->has('name')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Field name is required'
                    ], 400);
                }

                if ($errors->has('group_id')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Group not found'
                    ], 404);
                }
            }

            $category = Category::find($id);
            if (!$category || !is_null($category->deleted_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            if ($category->payment_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The category has generated transactions',
                    'payment_count' => $category->payment_count
                ], 409);
            }

            $category->name = $request->name;
            $category->group_id = $request->group_id;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Update category successfully',
                'category' => $category
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function catogory_delete($id)
    {
        try {
            $category = Category::find($id);


            if (!$category || !is_null($category->deleted_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            if ($category->payment_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The category has generated transactions',
                    'payment_count' => $category->payment_count
                ], 409);
            }

            $category->deleted_at = now();
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Deleted category successfully',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'group_id' => $category->group_id,
                    'created_at' => $category->created_at->toDateTimeString(),
                    'updated_at' => $category->updated_at->toDateTimeString(),
                    'deleted_at' => $category->deleted_at->toDateTimeString()
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
