@php
    use App\Models\MonthlyPeriod;
@endphp

<x-base-layout>

    <div class="card mb-7">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">{{ __('Relatório de Gastos x Previsão') }}</h2>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="GET" action="{{ route('reports.expenses-vs-forecast') }}" class="row g-5 align-items-end">
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
                        {{ __('Gerar relatório') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-5 g-xl-8 mb-7">
        <div class="col-md-3">
            <div class="card card-flush h-100 border border-primary border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Período') }}</span>
                    <span class="fs-3 fw-bolder text-gray-900 mt-2">{{ $selectedMonthLabel }}/{{ $selectedYear }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100 border border-info border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Total previsto') }}</span>
                    <span class="fs-3 fw-bolder text-info mt-2">{{ MonthlyPeriod::formatMoney($totalForecast) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100 border border-danger border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Total realizado') }}</span>
                    <span class="fs-3 fw-bolder text-danger mt-2">{{ MonthlyPeriod::formatMoney($totalRealized) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100 border border-warning border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Desvio total') }}</span>
                    <span class="fs-3 fw-bolder mt-2 {{ $totalVariance > 0 ? 'text-danger' : ($totalVariance < 0 ? 'text-success' : 'text-gray-900') }}">
                        {{ MonthlyPeriod::formatMoney($totalVariance) }}
                    </span>
                    <span class="text-muted fs-8 mt-1">
                        @if ($totalVariancePercent === null)
                            {{ __('Sem base de previsão') }}
                        @else
                            {{ number_format($totalVariancePercent, 2, ',', '.') }}%
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title fw-bolder text-gray-900">{{ __('Comparativo por categoria') }}</h3>
        </div>
        <div class="card-body pt-2">
            @if ($comparisonRows->isEmpty())
                <div class="alert alert-info d-flex align-items-center mb-0">
                    {!! theme()->getSvgIcon('icons/duotune/general/gen048.svg', 'svg-icon-2x svg-icon-info me-3') !!}
                    <div>{{ __('Não há dados de previsão ou lançamentos de gastos para o período selecionado.') }}</div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th>{{ __('Categoria') }}</th>
                            <th class="text-end">{{ __('Previsto') }}</th>
                            <th class="text-end">{{ __('Realizado') }}</th>
                            <th class="text-end">{{ __('Desvio') }}</th>
                            <th class="text-end">{{ __('Desvio (%)') }}</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">
                        @foreach ($comparisonRows as $row)
                            <tr>
                                <td>{{ $row->category_name }}</td>
                                <td class="text-end">{{ MonthlyPeriod::formatMoney($row->forecast) }}</td>
                                <td class="text-end">{{ MonthlyPeriod::formatMoney($row->realized) }}</td>
                                <td class="text-end {{ $row->variance > 0 ? 'text-danger' : ($row->variance < 0 ? 'text-success' : '') }}">
                                    {{ MonthlyPeriod::formatMoney($row->variance) }}
                                </td>
                                <td class="text-end">
                                    @if ($row->variance_percent === null)
                                        —
                                    @else
                                        {{ number_format($row->variance_percent, 2, ',', '.') }}%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</x-base-layout>
