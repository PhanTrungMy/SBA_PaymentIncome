<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function catogory_show_all(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $name = $request->query('name');
        $groupId = $request->query('group_id');
        $reportType = $request->query('report_type');

        $query = Category::with('group')
            ->join('groups', 'categories.group_id', '=', 'groups.id')
            ->select('categories.*', 'groups.report_type');

        if (!empty($name)) {
            $query->where('categories.name', 'LIKE', "%{$name}%");
        }

        if (!empty($groupId)) {
            $query->where('categories.group_id', $groupId);
        }

        if (!empty($reportType)) {
            $query->where('groups.report_type', $reportType);
        }

        $categories = $query->paginate($perPage, ['*'], 'page', $page);

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

    public function category_delete(Request $request)
    {
        $ids = $request->input('id');

        $categories = Category::whereIn('id', $ids)->get();


        if ($categories->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        foreach ($categories as $category) {
            if ($category->payment_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The category has generated transactions',
                    'payment_count' => $category->payment_count
                ], 409);
            }
        }

        $deleted = $categories->each(function ($category) {
            $category->deleted_at = now();
            $category->save();
        });

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed'
            ], 500);
        }

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
    }
}
