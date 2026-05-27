<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\MonthlyEntry;
use App\Models\MonthlyExpenseForecast;
use App\Models\MonthlyPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function expensesVsForecast(Request $request): View
    {
        $currentDate = Carbon::now();
        $selectedYear = (int) $request->query('year', $currentDate->year);
        $selectedMonth = (int) $request->query('month', $currentDate->month);

        $monthOptions = $this->monthOptions();
        if (!array_key_exists($selectedMonth, $monthOptions)) {
            $selectedMonth = (int) $currentDate->month;
        }

        $currentYear = (int) $currentDate->year;
        $years = range($currentYear - 2, $currentYear + 3);

        $forecastByCategory = MonthlyExpenseForecast::query()
            ->select('expense_category_id', DB::raw('SUM(expected_amount) as total_forecast'))
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->groupBy('expense_category_id')
            ->pluck('total_forecast', 'expense_category_id');

        $realizedByCategory = MonthlyEntry::query()
            ->select('monthly_entries.expense_category_id', DB::raw('SUM(monthly_entries.amount) as total_realized'))
            ->join('monthly_periods', 'monthly_periods.id', '=', 'monthly_entries.monthly_period_id')
            ->where('monthly_entries.type', MonthlyEntry::TYPE_EXPENSE)
            ->whereNotNull('monthly_entries.expense_category_id')
            ->where('monthly_periods.year', $selectedYear)
            ->where('monthly_periods.month', $selectedMonth)
            ->groupBy('monthly_entries.expense_category_id')
            ->pluck('total_realized', 'monthly_entries.expense_category_id');

        $categoryIds = collect($forecastByCategory->keys())
            ->merge($realizedByCategory->keys())
            ->unique()
            ->values();

        $categories = ExpenseCategory::query()
            ->whereIn('id', $categoryIds)
            ->orderBy('name')
            ->get();

        $comparisonRows = $categories->map(function (ExpenseCategory $category) use ($forecastByCategory, $realizedByCategory) {
            $forecast = (float) ($forecastByCategory[$category->id] ?? 0);
            $realized = (float) ($realizedByCategory[$category->id] ?? 0);
            $variance = $realized - $forecast;
            $variancePercent = $forecast > 0 ? ($variance / $forecast) * 100 : null;

            return (object) [
                'category_name' => $category->name,
                'forecast' => $forecast,
                'realized' => $realized,
                'variance' => $variance,
                'variance_percent' => $variancePercent,
            ];
        });

        $totalForecast = (float) $comparisonRows->sum('forecast');
        $totalRealized = (float) $comparisonRows->sum('realized');
        $totalVariance = $totalRealized - $totalForecast;
        $totalVariancePercent = $totalForecast > 0 ? ($totalVariance / $totalForecast) * 100 : null;
        $selectedMonthLabel = $monthOptions[$selectedMonth];

        return view('pages.reports.expenses-vs-forecast', compact(
            'years',
            'monthOptions',
            'selectedYear',
            'selectedMonth',
            'selectedMonthLabel',
            'comparisonRows',
            'totalForecast',
            'totalRealized',
            'totalVariance',
            'totalVariancePercent'
        ));
    }

    /**
     * @return array<int, string>
     */
    private function monthOptions(): array
    {
        return [
            1  => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5  => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9  => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];
    }
}
