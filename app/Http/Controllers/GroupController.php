<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function get_all_groups(Request $request)
    {
        try {
            $resultGroups = [];
            $perPage = $request->query('per_page') ?? 10;
            $perPage = in_array($perPage, [10, 20]) ? $perPage : 10;
            $curPage = $request->query('page') ?? 1;
            $name = $request->query('name');
            $reportType = $request->query('report_type');

            $query = DB::table('groups')
                ->select('id', 'name', 'report_type', 'created_at', 'updated_at');
            if ($name !== null) {
                $query->where('name', 'like', '%' . $name . '%');
            }
            if ($reportType !== null) {
                $query->where('report_type', $reportType);
            }

            $groups = $query->paginate($perPage);

            foreach ($groups as $group) {
                $resultGroups[] = [
                    "id" => $group->id,
                    'name' => $group->name,
                    'report_type' => $group->report_type,
                    'created_at' => $group->created_at,
                    'updated_at' => $group->updated_at,
                ];
            }

            $pagination = [
                'per_page' => $groups->perPage(),
                'current_page' => $groups->currentPage(),
                'total_pages' => $groups->lastPage(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get all groups successfully',
                'total_result' => $groups->total(),
                'pagination' => $pagination,
                'groups' => $resultGroups,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
