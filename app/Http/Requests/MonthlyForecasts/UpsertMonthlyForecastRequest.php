<?php

namespace App\Http\Requests\MonthlyForecasts;

use App\Models\MonthlyPeriod;
use Illuminate\Foundation\Http\FormRequest;

class UpsertMonthlyForecastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'forecasts' => ['required', 'array'],
            'forecasts.*.expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'forecasts.*.expected_amount' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
        ];
    }

    public function attributes(): array
    {
        return [
            'year' => __('Ano'),
            'month' => __('Mês'),
            'forecasts' => __('Previsões'),
            'forecasts.*.expense_category_id' => __('Categoria de gasto'),
            'forecasts.*.expected_amount' => __('Valor previsto'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $forecasts = $this->input('forecasts', []);

        if (!is_array($forecasts)) {
            return;
        }

        foreach ($forecasts as $index => $forecast) {
            $amount = $forecast['expected_amount'] ?? null;
            $parsedAmount = MonthlyPeriod::parseMoneyInput($amount);

            $forecasts[$index]['expected_amount'] = $parsedAmount ?? '0.00';
        }

        $this->merge([
            'forecasts' => $forecasts,
        ]);
    }
}
