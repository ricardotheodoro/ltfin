<?php

namespace App\Http\Requests\MonthlyEntries;

use App\Models\MonthlyEntry;
use App\Models\MonthlyPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMonthlyEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in([MonthlyEntry::TYPE_EXPENSE, MonthlyEntry::TYPE_INCOME])],
            'expense_category_id' => [
                Rule::requiredIf($this->input('type') === MonthlyEntry::TYPE_EXPENSE),
                'nullable', 'integer', 'exists:expense_categories,id',
            ],
            'income_category_id' => [
                Rule::requiredIf($this->input('type') === MonthlyEntry::TYPE_INCOME),
                'nullable', 'integer', 'exists:income_categories,id',
            ],
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'description' => ['nullable', 'string', 'max:500'],
            'entry_date'  => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'type'                => __('Tipo'),
            'expense_category_id' => __('Categoria de gasto'),
            'income_category_id'  => __('Categoria de recebimento'),
            'amount'              => __('Valor'),
            'description'         => __('Descrição'),
            'entry_date'          => __('Data'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $period = $this->route('monthly_period');

            if (!$period || !$this->filled('entry_date')) {
                return;
            }

            $date = \Carbon\Carbon::parse($this->input('entry_date'));

            if ((int) $date->year !== (int) $period->year || (int) $date->month !== (int) $period->month) {
                $validator->errors()->add(
                    'entry_date',
                    __('A data deve pertencer ao mês do lançamento (:period).', ['period' => $period->label])
                );
            }
        });
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('type') === MonthlyEntry::TYPE_EXPENSE) {
            $this->merge(['income_category_id' => null]);
        }

        if ($this->input('type') === MonthlyEntry::TYPE_INCOME) {
            $this->merge(['expense_category_id' => null]);
        }

        if ($this->has('amount')) {
            $parsed = MonthlyPeriod::parseMoneyInput($this->input('amount'));
            if ($parsed !== null) {
                $this->merge(['amount' => $parsed]);
            }
        }
    }
}
