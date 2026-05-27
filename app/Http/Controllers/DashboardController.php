<?php

namespace App\Http\Controllers;

use App\Models\MonthlyEntry;
use App\Models\MonthlyPeriod;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $periods = MonthlyPeriod::query()
            ->withSum(['entries as expense_sum' => function ($query) {
                $query->where('type', MonthlyEntry::TYPE_EXPENSE);
            }], 'amount')
            ->withSum(['entries as income_sum' => function ($query) {
                $query->where('type', MonthlyEntry::TYPE_INCOME);
            }], 'amount')
            ->orderBy('year')
            ->orderBy('month')
            ->limit(12)
            ->get();

        $chartLabels = $periods->map(fn (MonthlyPeriod $p) => $p->label)->values()->all();
        $chartExpenses = $periods->map(fn (MonthlyPeriod $p) => round((float) ($p->expense_sum ?? 0), 2))->values()->all();
        $chartIncome = $periods->map(fn (MonthlyPeriod $p) => round((float) ($p->income_sum ?? 0), 2))->values()->all();
        $chartBalance = $periods->map(function (MonthlyPeriod $p) {
            return round((float) ($p->income_sum ?? 0) - (float) ($p->expense_sum ?? 0), 2);
        })->values()->all();

        $latestPeriod = MonthlyPeriod::query()
            ->withSum(['entries as expense_sum' => function ($query) {
                $query->where('type', MonthlyEntry::TYPE_EXPENSE);
            }], 'amount')
            ->withSum(['entries as income_sum' => function ($query) {
                $query->where('type', MonthlyEntry::TYPE_INCOME);
            }], 'amount')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        $totalExpenses = (float) MonthlyEntry::where('type', MonthlyEntry::TYPE_EXPENSE)->sum('amount');
        $totalIncome   = (float) MonthlyEntry::where('type', MonthlyEntry::TYPE_INCOME)->sum('amount');

        return view('pages.dashboard.index', compact(
            'chartLabels',
            'chartExpenses',
            'chartIncome',
            'chartBalance',
            'latestPeriod',
            'totalExpenses',
            'totalIncome'
        ));
    }
}
