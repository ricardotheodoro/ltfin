<?php

namespace App\Http\Controllers;

use App\DataTables\MonthlyPeriodsDataTable;
use App\Exports\MonthlyPeriodBalanceExport;
use App\Http\Requests\MonthlyPeriods\StoreMonthlyPeriodRequest;
use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Models\MonthlyPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonthlyPeriodsController extends Controller
{
    public function index(MonthlyPeriodsDataTable $dataTable)
    {
        return $dataTable->render('pages.monthly-periods.index');
    }

    public function create(): View
    {
        $currentYear  = (int) date('Y');
        $years        = range($currentYear - 2, $currentYear + 2);
        $monthOptions = $this->monthOptions();

        return view('pages.monthly-periods.create', compact('years', 'monthOptions'));
    }

    public function store(StoreMonthlyPeriodRequest $request): RedirectResponse
    {
        $period = MonthlyPeriod::create($request->validated());

        return redirect()
            ->route('monthly-periods.show', $period)
            ->with('success', __('Mês cadastrado. Adicione os lançamentos de gastos e recebíveis.'));
    }

    public function show(MonthlyPeriod $monthlyPeriod): View
    {
        $monthlyPeriod->load([
            'entries' => fn ($q) => $q->with(['expenseCategory', 'incomeCategory'])->orderBy('entry_date')->orderBy('id'),
        ]);

        $expenseEntries = $monthlyPeriod->entries->where('type', 'expense');
        $incomeEntries  = $monthlyPeriod->entries->where('type', 'income');

        $expenseCategories = ExpenseCategory::active()->orderBy('name')->get();
        $incomeCategories  = IncomeCategory::active()->orderBy('name')->get();

        $periodStart = Carbon::create($monthlyPeriod->year, $monthlyPeriod->month, 1)->startOfDay();
        $periodEnd   = $periodStart->copy()->endOfMonth();
        $today       = now()->startOfDay();

        if ($today->between($periodStart, $periodEnd)) {
            $defaultEntryDate = $today->toDateString();
        } elseif ($today->lt($periodStart)) {
            $defaultEntryDate = $periodStart->toDateString();
        } else {
            $defaultEntryDate = $periodEnd->toDateString();
        }

        return view('pages.monthly-periods.show', compact(
            'monthlyPeriod',
            'expenseEntries',
            'incomeEntries',
            'expenseCategories',
            'incomeCategories',
            'defaultEntryDate'
        ));
    }

    public function destroy(MonthlyPeriod $monthlyPeriod): RedirectResponse
    {
        $monthlyPeriod->delete();

        return redirect()
            ->route('monthly-periods.index')
            ->with('success', __('Lançamento mensal excluído com sucesso.'));
    }

    public function close(MonthlyPeriod $monthlyPeriod): RedirectResponse
    {
        if ($monthlyPeriod->is_closed) {
            return redirect()
                ->route('monthly-periods.show', $monthlyPeriod)
                ->with('error', __('Este mês já está fechado.'));
        }

        $monthlyPeriod->update(['is_closed' => true]);

        return redirect()
            ->route('monthly-periods.show', $monthlyPeriod)
            ->with('success', __('Mês fechado. Não é possível alterar lançamentos até reabrir.'));
    }

    public function reopen(MonthlyPeriod $monthlyPeriod): RedirectResponse
    {
        if (!$monthlyPeriod->is_closed) {
            return redirect()
                ->route('monthly-periods.show', $monthlyPeriod)
                ->with('error', __('Este mês já está aberto.'));
        }

        $monthlyPeriod->update(['is_closed' => false]);

        return redirect()
            ->route('monthly-periods.show', $monthlyPeriod)
            ->with('success', __('Mês reaberto. Você pode editar os lançamentos novamente.'));
    }

    public function exportCsv(MonthlyPeriod $monthlyPeriod): StreamedResponse
    {
        return (new MonthlyPeriodBalanceExport($monthlyPeriod))->toCsvResponse();
    }

    public function exportPdf(MonthlyPeriod $monthlyPeriod): View
    {
        $monthlyPeriod->load([
            'entries' => fn ($q) => $q->with(['expenseCategory', 'incomeCategory'])
                ->orderBy('entry_date')
                ->orderBy('id'),
        ]);

        $expenseEntries = $monthlyPeriod->entries->where('type', 'expense');
        $incomeEntries  = $monthlyPeriod->entries->where('type', 'income');

        return view('pages.monthly-periods.export-pdf', compact(
            'monthlyPeriod',
            'expenseEntries',
            'incomeEntries'
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
