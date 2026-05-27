<div class="modal fade" id="modal-edit-entry" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form-edit-entry" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title fw-bolder">{{ __('Editar lançamento') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">{{ __('Tipo') }}</label>
                            <select name="type" id="edit-entry-type" class="form-select form-select-solid" required>
                                <option value="expense">{{ __('Gasto') }}</option>
                                <option value="income">{{ __('Recebível') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="edit-expense-category-wrap">
                            <label class="form-label fw-bold required">{{ __('Categoria de gasto') }}</label>
                            <select name="expense_category_id" id="edit-expense-category-id" class="form-select form-select-solid">
                                <option value="">{{ __('Selecione...') }}</option>
                                @foreach ($expenseCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 d-none" id="edit-income-category-wrap">
                            <label class="form-label fw-bold required">{{ __('Categoria de recebimento') }}</label>
                            <select name="income_category_id" id="edit-income-category-id" class="form-select form-select-solid">
                                <option value="">{{ __('Selecione...') }}</option>
                                @foreach ($incomeCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold required">{{ __('Valor') }}</label>
                            @include('pages.monthly-periods._amount-field', [
                                'name'     => 'amount',
                                'id'       => 'edit-entry-amount',
                                'required' => true,
                            ])
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold required">{{ __('Data') }}</label>
                            <input type="date" name="entry_date" id="edit-entry-date" class="form-control form-control-solid" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">{{ __('Descrição') }}</label>
                            <input type="text" name="description" id="edit-entry-description" maxlength="500" class="form-control form-control-solid">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Salvar alterações') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('modal-edit-entry');
            if (!modalEl) return;

            const form = document.getElementById('form-edit-entry');
            const typeSelect = document.getElementById('edit-entry-type');
            const expenseWrap = document.getElementById('edit-expense-category-wrap');
            const incomeWrap = document.getElementById('edit-income-category-wrap');
            const expenseSelect = document.getElementById('edit-expense-category-id');
            const incomeSelect = document.getElementById('edit-income-category-id');

            function toggleCategoryFields() {
                const isExpense = typeSelect.value === 'expense';
                expenseWrap.classList.toggle('d-none', !isExpense);
                incomeWrap.classList.toggle('d-none', isExpense);
                expenseSelect.required = isExpense;
                incomeSelect.required = !isExpense;
            }

            typeSelect.addEventListener('change', toggleCategoryFields);

            document.querySelectorAll('[data-edit-entry]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    form.action = btn.getAttribute('data-action');
                    typeSelect.value = btn.getAttribute('data-type');
                    expenseSelect.value = btn.getAttribute('data-expense-category-id') || '';
                    incomeSelect.value = btn.getAttribute('data-income-category-id') || '';
                    const amountInput = document.getElementById('edit-entry-amount');
                    const rawAmount = btn.getAttribute('data-amount');
                    amountInput.value = window.LTFinMoney
                        ? window.LTFinMoney.formatForInput(parseFloat(rawAmount))
                        : rawAmount;
                    if (window.LTFinMoney) {
                        window.LTFinMoney.bind(amountInput);
                    }
                    document.getElementById('edit-entry-date').value = btn.getAttribute('data-entry-date');
                    document.getElementById('edit-entry-description').value = btn.getAttribute('data-description') || '';
                    toggleCategoryFields();
                    new bootstrap.Modal(modalEl).show();
                });
            });
        });
    </script>
@endpush
