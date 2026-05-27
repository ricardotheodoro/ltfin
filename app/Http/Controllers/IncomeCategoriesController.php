<?php

namespace App\Http\Controllers;

use App\DataTables\IncomeCategoriesDataTable;
use App\Http\Requests\IncomeCategories\StoreIncomeCategoryRequest;
use App\Http\Requests\IncomeCategories\UpdateIncomeCategoryRequest;
use App\Models\IncomeCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class IncomeCategoriesController extends Controller
{
    public function index(IncomeCategoriesDataTable $dataTable)
    {
        return $dataTable->render('pages.income-categories.index');
    }

    public function create(): View
    {
        $incomeCategory = new IncomeCategory(['is_active' => true]);

        return view('pages.income-categories.create', compact('incomeCategory'));
    }

    public function store(StoreIncomeCategoryRequest $request): RedirectResponse
    {
        IncomeCategory::create($request->validated());

        return redirect()
            ->route('income-categories.index')
            ->with('success', __('Categoria de recebimento criada com sucesso.'));
    }

    public function edit(IncomeCategory $incomeCategory): View
    {
        return view('pages.income-categories.edit', compact('incomeCategory'));
    }

    public function update(
        UpdateIncomeCategoryRequest $request,
        IncomeCategory $incomeCategory
    ): RedirectResponse {
        $incomeCategory->update($request->validated());

        return redirect()
            ->route('income-categories.index')
            ->with('success', __('Categoria de recebimento atualizada com sucesso.'));
    }

    public function destroy(IncomeCategory $incomeCategory): RedirectResponse
    {
        $incomeCategory->delete();

        return redirect()
            ->route('income-categories.index')
            ->with('success', __('Categoria de recebimento excluída com sucesso.'));
    }
}
