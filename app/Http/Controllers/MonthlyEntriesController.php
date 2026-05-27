<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonthlyEntries\StoreMonthlyEntryRequest;
use App\Http\Requests\MonthlyEntries\UpdateMonthlyEntryRequest;
use App\Models\MonthlyEntry;
use App\Models\MonthlyPeriod;
use Illuminate\Http\RedirectResponse;

class MonthlyEntriesController extends Controller
{
    public function store(StoreMonthlyEntryRequest $request, MonthlyPeriod $monthlyPeriod): RedirectResponse
    {
        if ($monthlyPeriod->is_closed) {
            return redirect()
                ->route('monthly-periods.show', $monthlyPeriod)
                ->with('error', __('Este mês está fechado. Não é possível adicionar lançamentos.'));
        }

        $monthlyPeriod->entries()->create($request->validated());

        $message = $request->input('type') === MonthlyEntry::TYPE_EXPENSE
            ? __('Gasto lançado com sucesso.')
            : __('Recebível lançado com sucesso.');

        return redirect()
            ->route('monthly-periods.show', $monthlyPeriod)
            ->with('success', $message);
    }

    public function update(
        UpdateMonthlyEntryRequest $request,
        MonthlyPeriod $monthlyPeriod,
        MonthlyEntry $monthlyEntry
    ): RedirectResponse {
        if ($monthlyPeriod->is_closed) {
            return redirect()
                ->route('monthly-periods.show', $monthlyPeriod)
                ->with('error', __('Este mês está fechado. Não é possível editar lançamentos.'));
        }

        if ($monthlyEntry->monthly_period_id !== $monthlyPeriod->id) {
            abort(404);
        }

        $monthlyEntry->update($request->validated());

        return redirect()
            ->route('monthly-periods.show', $monthlyPeriod)
            ->with('success', __('Lançamento atualizado com sucesso.'));
    }

    public function destroy(MonthlyPeriod $monthlyPeriod, MonthlyEntry $monthlyEntry): RedirectResponse
    {
        if ($monthlyPeriod->is_closed) {
            return redirect()
                ->route('monthly-periods.show', $monthlyPeriod)
                ->with('error', __('Este mês está fechado. Não é possível excluir lançamentos.'));
        }

        if ($monthlyEntry->monthly_period_id !== $monthlyPeriod->id) {
            abort(404);
        }

        $monthlyEntry->delete();

        return redirect()
            ->route('monthly-periods.show', $monthlyPeriod)
            ->with('success', __('Lançamento removido com sucesso.'));
    }
}
