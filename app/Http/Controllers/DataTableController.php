<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Group;
use App\Models\Category;
use App\Models\Payment;
use App\Models\ExchangeRate;
use App\Models\BalanceSheet;

class DataTableController extends Controller
{
    public function get_data_table_pl(Request $request)
    {
        $year = $request->input('y', date('Y'));
        $type = $request->input('type', 'pl');

        $responseData = $this->fetchAndFormatData($year, $type);

        return response()->json($responseData, 200);
    }
    private function fetchAndFormatData($year, $type)
    {
        $data = [];
        $groups = Group::where('report_type', $type)->get();
        $totalMonths = [];

        foreach ($groups as $group) {
            $groupData = [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'categories' => []
            ];

            $totalMonth = array_fill_keys($this->generateMonthKeys($year), 0);
            $categories = Category::where('group_id', $group->id)->get();

            foreach ($categories as $category) {
                $categoryData = [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'data' => $this->calculateMonthlyTotalsForCategory($category->id, $year)
                ];

                array_push($groupData['categories'], $categoryData);

                foreach ($categoryData['data'] as $month => $value) {
                    if ($month != 'total') {
                        $totalMonth[$month] += $value;
                    }
                }
            }

            $totalMonth['total'] = round(array_sum($totalMonth), 3);
            $groupData['total_month'] = $totalMonth;
            $totalMonths[$group->id] = $totalMonth;
            array_push($data, $groupData);
        }
        foreach ($data as &$groupData) {
            switch ($groupData['group_id']) {
                case 7:
                    if (isset($totalMonths[1]) && isset($totalMonths[6])) {
                        $groupData['total_month'] = $this->calculateDifference($totalMonths[1], $totalMonths[6]);
                        $totalMonths[7] = $this->calculateDifference($totalMonths[1], $totalMonths[6]);
                    }
                    break;
                case 9:
                    if (isset($totalMonths[7]) and isset($totalMonths[8])) {
                        $groupData['total_month'] = $this->calculateSum($totalMonths[7], $totalMonths[8]);
                        $totalMonths[9] = $this->calculateSum($totalMonths[7], $totalMonths[8]);
                    }
                    break;
                case 10:
                    if (isset($totalMonths[9])) {
                        $categoryData = $this->calculateMonthlyTotalsForCategory(42, $year);
                        $groupData['total_month'] = $this->calculateDifference($totalMonths[9], $categoryData);
                    }
                    break;
            }
        }

        return $data;
    }
    private function generateMonthKeys($year)
    {
        $months = [];
        $startDate = Carbon::createFromDate($year, 4, 1);
        $endDate = Carbon::createFromDate($year + 1, 3, 31);

        for ($month = $startDate; $month->lte($endDate); $month->addMonth()) {
            $months[] = $month->format('Y-m');
        }

        return $months;
    }
    private function calculateMonthlyTotalsForCategory($categoryId, $year)
    {
        $monthlyTotals = [];
        $total = 0;

        $startDate = Carbon::createFromDate($year, 4, 1);
        $endDate = Carbon::createFromDate($year + 1, 3, 31);

        for ($month = $startDate; $month->lte($endDate); $month->addMonth()) {
            $monthEnd = $month->copy()->endOfMonth();

            $payments = Payment::where('category_id', $categoryId)
                ->whereBetween('payment_date', [$month, $monthEnd])
                ->get();

            $monthlyCost = 0;
            foreach ($payments as $payment) {
                $exchangeRate = ExchangeRate::find($payment->exchange_rate_id);
                $convertedCost = round($payment->cost / $exchangeRate->jpy, 3);
                $monthlyCost += $convertedCost;
            }

            $monthlyTotals[$month->format('Y-m')] = $monthlyCost;
            $total += $monthlyCost;
        }

        $monthlyTotals['total'] = round($total, 3);
        return $monthlyTotals;
    }
    private function calculateSum($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            $result[$key] = $value + ($array2[$key] ?? 0);
        }
        return $result;
    }
    private function calculateDifference($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            $result[$key] = $value - ($array2[$key] ?? 0);
        }
        return $result;
    }
}
