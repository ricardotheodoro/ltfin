@php
    use App\Models\MonthlyPeriod;
@endphp

<x-base-layout>

    <div class="card mb-7">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">{{ __('Previsão Mensal') }}</h2>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="GET" action="{{ route('monthly-forecasts') }}" class="row g-5 align-items-end">
                <div class="col-md-3">
                    <label for="month" class="form-label required">{{ __('Mês de referência') }}</label>
                    <select id="month" name="month" class="form-select form-select-solid" required>
                        @foreach ($monthOptions as $monthValue => $monthLabel)
                            <option value="{{ $monthValue }}" {{ (int) $selectedMonth === (int) $monthValue ? 'selected' : '' }}>
                                {{ $monthLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label required">{{ __('Ano') }}</label>
                    <select id="year" name="year" class="form-select form-select-solid" required>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        {!! theme()->getSvgIcon('icons/duotune/arrows/arr075.svg', 'svg-icon-2') !!}
                        {{ __('Abrir previsão') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-6">
            {!! theme()->getSvgIcon('icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-success me-3') !!}
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-5 g-xl-8 mb-7">
        <div class="col-md-4">
            <div class="card card-flush h-100 border border-primary border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Mês selecionado') }}</span>
                    <span class="fs-2 fw-bolder text-gray-900 mt-2">{{ $selectedMonthLabel }}/{{ $selectedYear }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush h-100 border border-danger border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Total previsto em gastos') }}</span>
                    <span class="fs-2 fw-bolder text-danger mt-2" data-forecast-total>
                        {{ MonthlyPeriod::formatMoney($totalForecast) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title fw-bolder text-gray-900">{{ __('Previsão por categoria de gasto') }}</h3>
        </div>
        <div class="card-body pt-2">
            @if ($forecastByCategory->isEmpty())
                <div class="alert alert-warning d-flex align-items-center mb-0">
                    {!! theme()->getSvgIcon('icons/duotune/general/gen044.svg', 'svg-icon-2x svg-icon-warning me-3') !!}
                    <div>{{ __('Nenhuma categoria de gasto ativa encontrada para preencher a previsão.') }}</div>
                </div>
            @else
                <form method="POST" action="{{ route('monthly-forecasts.upsert') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>{{ __('Categoria') }}</th>
                                <th class="text-end">{{ __('Valor previsto') }}</th>
                                <th class="text-end">{{ __('Participação') }}</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            @foreach ($forecastByCategory as $index => $item)
                                @php
                                    $hiddenId = "forecast_amount_{$index}";
                                    $rawAmount = old("forecasts.$index.expected_amount", $item->expected_amount);
                                    $amount = (float) $rawAmount;
                                    $share = $totalForecast > 0 ? ($amount / $totalForecast) * 100 : 0;
                                    $displayAmount = MonthlyPeriod::formatMoney($amount);
                                    $hasAmountError = $errors->has("forecasts.$index.expected_amount");
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="forecasts[{{ $index }}][expense_category_id]" value="{{ $item->expense_category_id }}">
                                        {{ $item->category_name }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-2 flex-wrap">
                                            <span class="fw-bold text-gray-800 forecast-amount-display"
                                                  data-forecast-display-for="{{ $hiddenId }}">
                                                {{ $displayAmount }}
                                            </span>
                                            <input type="hidden"
                                                   name="forecasts[{{ $index }}][expected_amount]"
                                                   id="{{ $hiddenId }}"
                                                   class="js-forecast-amount"
                                                   value="{{ is_numeric($rawAmount) ? MonthlyPeriod::formatMoneyInput($rawAmount) : $rawAmount }}">
                                            <button type="button"
                                                    class="btn btn-sm {{ $hasAmountError ? 'btn-light-danger' : 'btn-light-primary' }}"
                                                    title="{{ __('Definir valor previsto') }}"
                                                    data-edit-forecast
                                                    data-hidden-id="{{ $hiddenId }}"
                                                    data-category-name="{{ e($item->category_name) }}"
                                                    data-amount="{{ number_format($amount, 2, '.', '') }}"
                                                    @if ($hasAmountError)
                                                        data-validation-error="{{ $errors->first("forecasts.$index.expected_amount") }}"
                                                    @endif>
                                                {!! theme()->getSvgIcon('icons/duotune/art/art005.svg', 'svg-icon-3') !!}
                                                <span class="d-none d-sm-inline ms-1">{{ __('Definir') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-end forecast-share" data-share-for="{{ $hiddenId }}">
                                        {{ number_format($share, 2, ',', '.') }}%
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-6">
                        <button type="submit" class="btn btn-primary">
                            {!! theme()->getSvgIcon('icons/duotune/general/gen037.svg', 'svg-icon-2') !!}
                            {{ __('Salvar previsão') }}
                        </button>
                    </div>
                </form>

                @include('pages.monthly-forecasts._forecast-amount-modal')
            @endif
        </div>
    </div>

    @if (!$forecastByCategory->isEmpty())
        @include('pages.monthly-periods._amount-field-scripts')

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const totalCard = document.querySelector('[data-forecast-total]');
                    const amountInputs = document.querySelectorAll('.js-forecast-amount');

                    function parseMoneyValue(raw) {
                        if (!raw || String(raw).trim() === '') {
                            return 0;
                        }
                        const normalized = String(raw).trim().replace(/\./g, '').replace(',', '.');
                        const value = parseFloat(normalized);
                        return isNaN(value) ? 0 : value;
                    }

                    function formatMoney(amount) {
                        return amount.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                        });
                    }

                    function formatPercent(value) {
                        return value.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }) + '%';
                    }

                    function updateShares() {
                        let total = 0;
                        amountInputs.forEach((input) => {
                            total += parseMoneyValue(input.value);
                        });

                        if (totalCard) {
                            totalCard.textContent = formatMoney(total);
                        }

                        amountInputs.forEach((input) => {
                            const shareCell = document.querySelector('[data-share-for="' + input.id + '"]');
                            if (!shareCell) {
                                return;
                            }
                            const amount = parseMoneyValue(input.value);
                            const share = total > 0 ? (amount / total) * 100 : 0;
                            shareCell.textContent = formatPercent(share);
                        });
                    }

                    document.addEventListener('forecast-amount-changed', updateShares);
                    updateShares();
                });
            </script>
        @endpush
    @endif

</x-base-layout>
