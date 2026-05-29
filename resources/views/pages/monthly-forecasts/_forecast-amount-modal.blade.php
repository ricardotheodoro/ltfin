<div class="modal fade" id="modal-forecast-amount" tabindex="-1" aria-labelledby="modal-forecast-amount-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bolder" id="modal-forecast-amount-title">{{ __('Valor previsto') }}</h5>
                    <div class="text-muted fs-7 mt-1" id="modal-forecast-category-name"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
            </div>

            <div class="modal-body">
                <label for="modal-forecast-amount-input" class="form-label fw-bold required">{{ __('Valor') }}</label>
                @include('pages.monthly-periods._amount-field', [
                    'name'     => '',
                    'id'       => 'modal-forecast-amount-input',
                    'required' => false,
                ])
                <div id="modal-forecast-amount-error" class="invalid-feedback d-none"></div>
            </div>

            <div class="modal-footer flex-nowrap">
                <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary flex-grow-1" id="modal-forecast-amount-apply">
                    {{ __('Aplicar') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('modal-forecast-amount');
            if (!modalEl) {
                return;
            }

            const modal = new bootstrap.Modal(modalEl);
            const amountInput = document.getElementById('modal-forecast-amount-input');
            const categoryLabel = document.getElementById('modal-forecast-category-name');
            const errorEl = document.getElementById('modal-forecast-amount-error');
            const applyBtn = document.getElementById('modal-forecast-amount-apply');

            let activeHiddenInput = null;
            let activeDisplayEl = null;
            let activeShareCell = null;
            let activeEditBtn = null;

            function parseMoneyValue(raw) {
                if (!raw || String(raw).trim() === '') {
                    return 0;
                }
                const normalized = String(raw).trim().replace(/\./g, '').replace(',', '.');
                const value = parseFloat(normalized);
                return isNaN(value) ? 0 : value;
            }

            function formatMoneyDisplay(amount) {
                return amount.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                });
            }

            function showFieldError(message) {
                if (!errorEl) {
                    return;
                }
                if (message) {
                    errorEl.textContent = message;
                    errorEl.classList.remove('d-none');
                    errorEl.classList.add('d-block');
                    amountInput.classList.add('is-invalid');
                } else {
                    errorEl.textContent = '';
                    errorEl.classList.add('d-none');
                    errorEl.classList.remove('d-block');
                    amountInput.classList.remove('is-invalid');
                }
            }

            document.querySelectorAll('[data-edit-forecast]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const hiddenId = btn.getAttribute('data-hidden-id');
                    activeHiddenInput = document.getElementById(hiddenId);
                    activeDisplayEl = document.querySelector('[data-forecast-display-for="' + hiddenId + '"]');
                    activeShareCell = document.querySelector('[data-share-for="' + hiddenId + '"]');
                    activeEditBtn = btn;

                    categoryLabel.textContent = btn.getAttribute('data-category-name') || '';
                    const rawAmount = btn.getAttribute('data-amount') || '0';
                    amountInput.value = window.LTFinMoney
                        ? window.LTFinMoney.formatForInput(parseFloat(rawAmount))
                        : rawAmount;
                    if (window.LTFinMoney) {
                        window.LTFinMoney.bind(amountInput);
                    }

                    const serverError = btn.getAttribute('data-validation-error');
                    showFieldError(serverError || '');

                    modal.show();
                    setTimeout(function () {
                        amountInput.focus();
                    }, 300);
                });
            });

            function applyAmount() {
                if (!activeHiddenInput) {
                    return;
                }

                const amount = parseMoneyValue(amountInput.value);
                if (amount < 0) {
                    showFieldError('{{ __('Informe um valor válido.') }}');
                    return;
                }

                activeHiddenInput.value = amountInput.value.trim() === ''
                    ? ''
                    : amountInput.value;
                if (activeDisplayEl) {
                    activeDisplayEl.textContent = formatMoneyDisplay(amount);
                }
                if (activeEditBtn) {
                    activeEditBtn.setAttribute('data-amount', String(amount));
                    activeEditBtn.classList.remove('btn-light-danger');
                }

                showFieldError('');
                modal.hide();
                document.dispatchEvent(new CustomEvent('forecast-amount-changed'));
            }

            applyBtn.addEventListener('click', applyAmount);

            amountInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    applyAmount();
                }
            });

            modalEl.addEventListener('hidden.bs.modal', function () {
                showFieldError('');
            });

            const firstErrorBtn = document.querySelector('[data-edit-forecast][data-validation-error]');
            if (firstErrorBtn) {
                firstErrorBtn.click();
            }
        });
    </script>
@endpush
