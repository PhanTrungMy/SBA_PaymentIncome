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
    
        $offset = ($curPage - 1) * $perPage;
        $groups = $query->offset($offset)
            ->limit($perPage)
            ->get();
    
        foreach ($groups as $group) {
            $resultGroups[] = [
                "id" => $group->id,
                'name' => $group->name,
                'report_type' => $group->report_type,
                'created_at' => $group->created_at,
                'updated_at' => $group->updated_at,
            ];
        }
    
        $totalGroups = $query->count();
        $totalPages = ceil($totalGroups / $perPage);
        $pagination = [
            'per_page' => $perPage,
            'current_page' => $curPage,
            'total_pages' => $totalPages,
        ];
    
        return response()->json([
            'success' => true,
            'message' => 'Get all groups successfully',
            'total_result' => $totalGroups,
            'pagination' => $pagination,
            'groups' => $resultGroups,
        ], 200);
    }

}

