<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonthlyForecasts\UpsertMonthlyForecastRequest;
use App\Models\ExpenseCategory;
use App\Models\MonthlyExpenseForecast;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class MonthlyForecastsController extends Controller
{
    public function index(Request $request): View
    {
        $currentDate = Carbon::now();
        $selectedYear = (int) $request->query('year', $currentDate->year);
        $selectedMonth = (int) $request->query('month', $currentDate->month);

        $monthOptions = $this->monthOptions();
        if (!array_key_exists($selectedMonth, $monthOptions)) {
            $selectedMonth = (int) $currentDate->month;
        }
        $selectedMonthLabel = $monthOptions[$selectedMonth];

        $currentYear = (int) $currentDate->year;
        $years = range($currentYear - 2, $currentYear + 3);

        $categories = ExpenseCategory::query()
            ->active()
            ->orderBy('name')
            ->get();

        $storedForecasts = MonthlyExpenseForecast::query()
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get()
            ->keyBy('expense_category_id');

        $forecastByCategory = $categories->map(function (ExpenseCategory $category) use ($storedForecasts) {
            $forecast = $storedForecasts->get($category->id);

            return (object) [
                'expense_category_id' => $category->id,
                'category_name' => $category->name,
                'expected_amount' => (float) ($forecast->expected_amount ?? 0),
            ];
        });

        $totalForecast = (float) $forecastByCategory->sum('expected_amount');

        return view('pages.monthly-forecasts.index', compact(
            'years',
            'monthOptions',
            'selectedYear',
            'selectedMonth',
            'selectedMonthLabel',
            'forecastByCategory',
            'totalForecast'
        ));
    }

    public function upsert(UpsertMonthlyForecastRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $year = (int) $validated['year'];
        $month = (int) $validated['month'];

        $upsertPayload = collect($validated['forecasts'])
            ->map(function (array $item) use ($year, $month) {
                return [
                    'year' => $year,
                    'month' => $month,
                    'expense_category_id' => (int) $item['expense_category_id'],
                    'expected_amount' => (float) $item['expected_amount'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ];
            })
            ->all();

        if (!empty($upsertPayload)) {
            MonthlyExpenseForecast::query()->upsert(
                $upsertPayload,
                ['year', 'month', 'expense_category_id'],
                ['expected_amount', 'updated_at']
            );
        }

        return redirect()
            ->route('monthly-forecasts', ['year' => $year, 'month' => $month])
            ->with('success', __('Previsão mensal salva com sucesso.'));
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
