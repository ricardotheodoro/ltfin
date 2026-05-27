<?php

namespace App\Exports;

use App\Models\MonthlyEntry;
use App\Models\MonthlyPeriod;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonthlyPeriodBalanceExport
{
    public function __construct(
        private MonthlyPeriod $period
    ) {
        $this->period->load([
            'entries' => fn ($q) => $q->with(['expenseCategory', 'incomeCategory'])
                ->orderBy('entry_date')
                ->orderBy('id'),
        ]);
    }

    public function toCsvResponse(): StreamedResponse
    {
        $filename = 'balanco-'.$this->period->year.'-'.str_pad((string) $this->period->month, 2, '0', STR_PAD_LEFT).'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Balanço mensal', $this->period->label], ';');
            fputcsv($handle, [], ';');
            fputcsv($handle, [
                'Tipo', 'Data', 'Categoria', 'Descrição', 'Valor (R$)',
            ], ';');

            foreach ($this->period->entries as $entry) {
                fputcsv($handle, [
                    $entry->type === MonthlyEntry::TYPE_EXPENSE ? 'Gasto' : 'Recebível',
                    $entry->entry_date->format('d/m/Y'),
                    $entry->category_name,
                    $entry->description ?? '',
                    number_format((float) $entry->amount, 2, ',', '.'),
                ], ';');
            }

            fputcsv($handle, [], ';');
            fputcsv($handle, ['Total gastos', '', '', '', MonthlyPeriod::formatMoney($this->period->total_expenses)], ';');
            fputcsv($handle, ['Total recebíveis', '', '', '', MonthlyPeriod::formatMoney($this->period->total_income)], ';');
            fputcsv($handle, ['Saldo', '', '', '', MonthlyPeriod::formatMoney($this->period->balance)], ';');

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
