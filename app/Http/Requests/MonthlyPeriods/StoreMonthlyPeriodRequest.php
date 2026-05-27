<?php

namespace App\Http\Requests\MonthlyPeriods;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMonthlyPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'year'  => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => [
                'required', 'integer', 'min:1', 'max:12',
                Rule::unique('monthly_periods')->where(function ($query) {
                    return $query->where('year', $this->input('year'));
                }),
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'year'  => __('Ano'),
            'month' => __('Mês'),
            'notes' => __('Observações'),
        ];
    }
}
