<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function get_all_groups(Request $request)
    {
        $resultGroups = [];
        $perPage = $request->query('per_page') ?? 10;
        $curPage = $request->query('cur_page') ?? 1;
        $groups = DB::select("
            SELECT `id`,`name`, `report_type`, `created_at`, `updated_at`
            FROM `groups`
            LIMIT :limit OFFSET :offset
        ", ['limit' => $perPage, 'offset' => ($curPage - 1) * $perPage]);
        
        foreach ($groups as $group) {
            $resultGroups[] = [
                "id" => $group->id,
                'name' => $group->name,
                'report_type' => $group->report_type,
                'created_at' => $group->created_at,
                'updated_at' => $group->updated_at,
            ];
        }
        
        if (!$resultGroups) {
            return response()->json([
                'status' => 'error',
                'message' => 'Groups not found'
            ], 404);
        }
        
        $totalGroups = DB::table('groups')->count();
        $totalPages = ceil($totalGroups / $perPage);
        $pagination = [
            'per_page' => $perPage,
            'current_page' => $curPage,
            'total_pages' => $totalPages,
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Get groups successfully',
            'total_result' => $totalGroups,
            'pagination' => $pagination,
            'groups' => $resultGroups
        ], 200);
}
}

