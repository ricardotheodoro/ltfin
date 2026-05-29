<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Models\MonthlyEntry;
use App\Models\MonthlyExpenseForecast;
use App\Models\MonthlyPeriod;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MonthlyFinanceSeeder extends Seeder
{
    public function run(): void
    {
        $expenseCategories = ExpenseCategory::query()->pluck('id', 'name');
        $incomeCategories  = IncomeCategory::query()->pluck('id', 'name');

        if ($expenseCategories->isEmpty() || $incomeCategories->isEmpty()) {
            return;
        }

        $current = Carbon::now();
        $previous = $current->copy()->subMonth();

        $this->seedPeriod($current->year, $current->month, $expenseCategories, $incomeCategories, false);
        $this->seedPeriod($previous->year, $previous->month, $expenseCategories, $incomeCategories, true);
        $this->seedForecasts($current->year, $current->month, $expenseCategories);
    }

    private function seedPeriod(
        int $year,
        int $month,
        $expenseCategories,
        $incomeCategories,
        bool $closed
    ): void {
        $period = MonthlyPeriod::query()->updateOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'notes'     => 'Período gerado pelo seeder de desenvolvimento.',
                'is_closed' => $closed,
            ]
        );

        MonthlyEntry::query()->where('monthly_period_id', $period->id)->delete();

        $entries = [
            [
                'type'                  => MonthlyEntry::TYPE_INCOME,
                'income_category_id'    => $incomeCategories['Salário'],
                'amount'                => 8500.00,
                'description'           => 'Salário mensal',
                'entry_date'            => Carbon::create($year, $month, 5)->toDateString(),
            ],
            [
                'type'                  => MonthlyEntry::TYPE_INCOME,
                'income_category_id'    => $incomeCategories['Freelance'],
                'amount'                => 1200.00,
                'description'           => 'Projeto freelance',
                'entry_date'            => Carbon::create($year, $month, 15)->toDateString(),
            ],
            [
                'type'                  => MonthlyEntry::TYPE_EXPENSE,
                'expense_category_id'   => $expenseCategories['Aluguel'],
                'amount'                => 2200.00,
                'description'           => 'Aluguel do mês',
                'entry_date'            => Carbon::create($year, $month, 10)->toDateString(),
            ],
            [
                'type'                  => MonthlyEntry::TYPE_EXPENSE,
                'expense_category_id'   => $expenseCategories['Alimentação'],
                'amount'                => 980.50,
                'description'           => 'Compras do mês',
                'entry_date'            => Carbon::create($year, $month, 12)->toDateString(),
            ],
            [
                'type'                  => MonthlyEntry::TYPE_EXPENSE,
                'expense_category_id'   => $expenseCategories['Transporte'],
                'amount'                => 450.00,
                'description'           => 'Transporte',
                'entry_date'            => Carbon::create($year, $month, 18)->toDateString(),
            ],
        ];

        foreach ($entries as $entry) {
            MonthlyEntry::query()->create(array_merge($entry, [
                'monthly_period_id' => $period->id,
            ]));
        }
    }

    private function seedForecasts(int $year, int $month, $expenseCategories): void
    {
        $forecasts = [
            'Aluguel'      => 2200.00,
            'Energia'      => 280.00,
            'Alimentação'  => 1000.00,
            'Transporte'   => 500.00,
            'Salários'     => 3500.00,
        ];

        foreach ($forecasts as $name => $amount) {
            if (! isset($expenseCategories[$name])) {
                continue;
            }

            MonthlyExpenseForecast::query()->updateOrCreate(
                [
                    'year'                => $year,
                    'month'               => $month,
                    'expense_category_id' => $expenseCategories[$name],
                ],
                ['expected_amount' => $amount]
            );
        }
    }
}
