<?php

namespace App\Http\Requests\IncomeCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIncomeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $id = $this->route('income_category')?->id ?? $this->route('income_category');

        return [
            'name'        => [
                'required', 'string', 'max:120',
                Rule::unique('income_categories', 'name')->ignore($id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => __('Nome'),
            'description' => __('Descrição'),
            'is_active'   => __('Ativo'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', false),
        ]);
    }
}
