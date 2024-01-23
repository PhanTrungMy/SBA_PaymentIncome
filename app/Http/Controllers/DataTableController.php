<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Group;
use App\Models\Category;
use App\Models\ExchangeRate;
use App\Models\BalanceSheet;
use App\Models\Payment;

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



    public function get_data_table_bs(Request $request)
    {
        $year = $request->input('y', date('Y'));
        $type = $request->input('type', 'bs');

        $responseData = $this->fetchAndFormatDatabs($year, $type);

        return response()->json($responseData, 200);
    }
    private function fetchAndFormatDatabs($year, $type)
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

            $totalMonth = array_fill_keys($this->generateMonthKeysbs($year), 0);
            $categories = Category::where('group_id', $group->id)->get();

            foreach ($categories as $category) {
                $categoryData = [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'data' => $this->calculateMonthlyTotalsForCategorybs($category->id, $year)
                ];

                array_push($groupData['categories'], $categoryData);

                foreach ($categoryData['data'] as $month => $value) {
                    if ($month != 'total') {
                        $totalMonth[$month] = ($totalMonth[$month] ?? 0) + $value;
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

                case 13:
                    if (isset($totalMonths[12]) and isset($totalMonths[11])) {
                        $groupData['total_month'] = $this->calculateSumbs($totalMonths[12], $totalMonths[11]);
                        $totalMonths[13] = $this->calculateSumbs($totalMonths[12], $totalMonths[11]);
                    }
                    break;
                case 15:
                    if (isset($totalMonths[14]) and isset($totalMonths[13])) {
                        $groupData['total_month'] = $this->calculateSumbs($totalMonths[14], $totalMonths[13]);
                        $totalMonths[15] = $this->calculateSumbs($totalMonths[14], $totalMonths[13]);
                    }
                    break;
                case 17:
                    if (isset($totalMonths[16])) {
                        $groupData['total_month'] = $totalMonths[16];
                    }
                case 18:
                    if (isset($totalMonths[17]) and isset($totalMonths[15])) {
                        $groupData['total_month'] = $this->calculateSumbs($totalMonths[17], $totalMonths[15]);
                        $totalMonths[18] = $this->calculateSumbs($totalMonths[17], $totalMonths[15]);
                    }
                    break;
                case 20:
                    if (isset($totalMonths[19])) {
                        $groupData['total_month'] = $totalMonths[19];
                    }
                case 21:
                    if (isset($totalMonths[20])) {
                        $groupData['total_month'] = $totalMonths[20];
                    }
                case 23:
                    if (isset($totalMonths[18]) && isset($totalMonths[21])) {
                        $groupData['total_month'] = $this->calculateDifferencebs($totalMonths[12], $totalMonths[11]);
                        $totalMonths[23] = $this->calculateDifferencebs($totalMonths[12], $totalMonths[11]);
                    }
                    break;
                case 25:
                    if (isset($totalMonths[23])) {
                        $groupData['total_month'] = $totalMonths[23];
                    }
                case 26:
                    if (isset($totalMonths[25])) {
                        $groupData['total_month'] = $totalMonths[25];
                    }

                case 27:
                    if (isset($totalMonths[22]) and isset($totalMonths[26])) {
                        $groupData['total_month'] = $this->calculateSumbs($totalMonths[22], $totalMonths[26]);
                        $totalMonths[27] = $this->calculateSumbs($totalMonths[22], $totalMonths[26]);
                    }
                    break;
                case 28:
                    if (isset($totalMonths[25])) {
                        $groupData['total_month'] = $totalMonths[27];
                    }
                case 29:
                    if (isset($totalMonths[28]) and isset($totalMonths[21])) {
                        $groupData['total_month'] = $this->calculateSumbs($totalMonths[28], $totalMonths[21]);
                        $totalMonths[29] = $this->calculateSumbs($totalMonths[28], $totalMonths[21]);
                    }
                    break;
                case 24:
                    if (isset($totalMonths[23])) {
                        $categoryData = $this->calculateMonthlyTotalsForCategorybs(56, $year);
                        $groupData['total_month'] = $this->calculateDifferencebs($totalMonths[23], $categoryData);
                        $totalMonths[24] = $this->calculateDifferencebs($totalMonths[23], $categoryData);
                    }
                    break;
            }
        }

        return $data;
    }
    private function generateMonthKeysbs($year)
    {
        $months = [];
        $startDate = Carbon::createFromDate($year, 4, 1);
        $endDate = Carbon::createFromDate($year + 1, 3, 31);

        for ($month = $startDate; $month->lte($endDate); $month->addMonth()) {
            $months[] = $month->format('Y-m');
        }

        return $months;
    }


    private function calculateMonthlyTotalsForCategorybs($categoryId, $year)
    {
        $monthlyTotals = [];
        $total = 0;
        $startDate = Carbon::createFromDate($year, 4, 1);
        $endDate = Carbon::createFromDate($year + 1, 3, 31);

        for ($month = $startDate; $month->lte($endDate); $month->addMonth()) {
            $monthEnd = $month->copy()->endOfMonth();
            $balances = BalanceSheet::where('category_id', $categoryId)
                ->where(function ($query) use ($month) {
                    $query->where('bs_month_year', 'like', $month->format('Y-m') . '%')
                        ->orWhere('bs_month_year', 'like', $month->format('Y') . '%');
                })
                ->get();
            $monthlyAmount = 0;
            foreach ($balances as $balance) {
                if ($month->format('Y-m') == substr($balance->bs_month_year, 0, 7)) {
                    $monthlyAmount += $balance->amount;
                }
            }
            $monthlyTotals[$month->format('Y-m')] = $monthlyAmount;
            $total += $monthlyAmount;
        }

        $previousYear = $year - 1;
        $previousYearBalance = BalanceSheet::where('category_id', $categoryId)
            ->where('bs_month_year', 'like', $previousYear . '%')
            ->first();

        $monthlyTotals[$previousYear] = $previousYearBalance ? $previousYearBalance->amount : null;

        $monthlyTotals['total'] = round($total, 3);
        return $monthlyTotals;
    }


    private function calculateSumbs($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            $result[$key] = $value + ($array2[$key] ?? 0);
        }
        return $result;
    }
    private function calculateDifferencebs($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            $result[$key] = $value - ($array2[$key] ?? 0);
        }
        return $result;
    }
}
