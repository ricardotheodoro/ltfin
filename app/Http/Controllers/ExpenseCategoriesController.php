<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseCategoriesDataTable;
use App\Http\Requests\ExpenseCategories\StoreExpenseCategoryRequest;
use App\Http\Requests\ExpenseCategories\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ExpenseCategoriesController extends Controller
{
    public function index(ExpenseCategoriesDataTable $dataTable)
    {
        return $dataTable->render('pages.expense-categories.index');
    }

    public function create(): View
    {
        $expenseCategory = new ExpenseCategory(['is_active' => true]);

        return view('pages.expense-categories.create', compact('expenseCategory'));
    }

    public function store(StoreExpenseCategoryRequest $request): RedirectResponse
    {
        ExpenseCategory::create($request->validated());

        return redirect()
            ->route('expense-categories.index')
            ->with('success', __('Categoria de gasto criada com sucesso.'));
    }

    public function edit(ExpenseCategory $expenseCategory): View
    {
        return view('pages.expense-categories.edit', compact('expenseCategory'));
    }

    public function update(
        UpdateExpenseCategoryRequest $request,
        ExpenseCategory $expenseCategory
    ): RedirectResponse {
        $expenseCategory->update($request->validated());

        return redirect()
            ->route('expense-categories.index')
            ->with('success', __('Categoria de gasto atualizada com sucesso.'));
    }

    public function destroy(ExpenseCategory $expenseCategory): RedirectResponse
    {
        $expenseCategory->delete();

        return redirect()
            ->route('expense-categories.index')
            ->with('success', __('Categoria de gasto excluída com sucesso.'));
    }
}
