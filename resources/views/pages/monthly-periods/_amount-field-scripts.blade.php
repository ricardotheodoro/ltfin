@push('scripts')
    <script>
        (function () {
            const LTFinMoney = {
                formatForInput(amount) {
                    if (amount === null || amount === '' || isNaN(amount)) {
                        return '';
                    }
                    return Number(amount).toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                },

                normalizeRawValue(raw) {
                    if (!raw || raw.trim() === '') {
                        return '';
                    }
                    const normalized = String(raw).trim().replace(',', '.');
                    if (/^\d+(\.\d+)?$/.test(normalized)) {
                        return this.formatForInput(parseFloat(normalized));
                    }
                    return raw;
                },

                bind(input) {
                    if (!input || input.dataset.moneyBound === '1') {
                        return;
                    }
                    input.dataset.moneyBound = '1';

                    input.value = this.normalizeRawValue(input.value);

                    input.addEventListener('input', function () {
                        let digits = this.value.replace(/\D/g, '');
                        if (digits.length > 13) {
                            digits = digits.slice(0, 13);
                        }
                        const amount = (parseInt(digits, 10) || 0) / 100;
                        this.value = amount.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        });
                    });

                    input.addEventListener('focus', function () {
                        setTimeout(() => this.select(), 0);
                    });
                },

                bindAll(root) {
                    (root || document).querySelectorAll('.js-money-amount').forEach((el) => this.bind(el));
                },
            };

            window.LTFinMoney = LTFinMoney;

            document.addEventListener('DOMContentLoaded', function () {
                LTFinMoney.bindAll();
            });
        })();
    </script>
@endpush
