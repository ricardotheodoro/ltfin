@php
    use App\Models\MonthlyPeriod;
@endphp

<x-base-layout>

    <div class="d-flex flex-wrap flex-stack mb-8">
        <div>
            <h1 class="fw-bolder text-gray-900 mb-1">{{ __('Dashboard financeiro') }}</h1>
            <p class="text-muted fs-6 mb-0">{{ __('Visão dos lançamentos mensais e balanço consolidado.') }}</p>
        </div>
        <a href="{{ route('monthly-periods.create') }}" class="btn btn-primary">
            {!! theme()->getSvgIcon('icons/duotune/arrows/arr075.svg', 'svg-icon-2') !!}
            {{ __('Novo mês') }}
        </a>
    </div>

    <div class="row g-5 g-xl-8 mb-8">
        <div class="col-md-4">
            <div class="card card-flush border border-danger border-dashed h-100">
                <div class="card-body">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Gastos (geral)') }}</span>
                    <span class="fs-2hx fw-bolder text-danger d-block mt-2">{{ MonthlyPeriod::formatMoney($totalExpenses) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush border border-success border-dashed h-100">
                <div class="card-body">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Recebíveis (geral)') }}</span>
                    <span class="fs-2hx fw-bolder text-success d-block mt-2">{{ MonthlyPeriod::formatMoney($totalIncome) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @php $globalBalance = $totalIncome - $totalExpenses; @endphp
            <div class="card card-flush border border-primary border-dashed h-100">
                <div class="card-body">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Saldo consolidado') }}</span>
                    <span class="fs-2hx fw-bolder d-block mt-2 {{ $globalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ MonthlyPeriod::formatMoney($globalBalance) }}
                    </span>
                    @if ($latestPeriod)
                        <a href="{{ route('monthly-periods.show', $latestPeriod) }}" class="text-primary fs-7 fw-bold mt-3 d-inline-block">
                            {{ __('Último mês:') }} {{ $latestPeriod->label }} →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-8">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title fw-bolder">{{ __('Evolução mensal') }}</h3>
        </div>
        <div class="card-body">
            @if (empty($chartLabels))
                <p class="text-muted text-center py-10 mb-0">{{ __('Cadastre lançamentos mensais para visualizar o gráfico.') }}</p>
            @else
                <div id="ltfin-monthly-chart" style="min-height: 360px;"></div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title fw-bolder">{{ __('Acesso rápido') }}</h3>
        </div>
        <div class="card-body pt-2 pb-8">
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('monthly-periods.index') }}" class="btn btn-light-primary">{{ __('Lançamentos mensais') }}</a>
                <a href="{{ route('expense-categories.index') }}" class="btn btn-light-danger">{{ __('Categorias de gastos') }}</a>
                <a href="{{ route('income-categories.index') }}" class="btn btn-light-success">{{ __('Categorias de recebimento') }}</a>
            </div>
        </div>
    </div>

    @if (!empty($chartLabels))
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('ltfin-monthly-chart');
                if (!el || typeof ApexCharts === 'undefined') {
                    return;
                }

                const options = {
                    series: [
                        { name: @json(__('Gastos')), data: @json($chartExpenses) },
                        { name: @json(__('Recebíveis')), data: @json($chartIncome) },
                        { name: @json(__('Saldo')), type: 'line', data: @json($chartBalance) },
                    ],
                    chart: {
                        type: 'bar',
                        height: 360,
                        toolbar: { show: true },
                        fontFamily: 'inherit',
                    },
                    plotOptions: {
                        bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 },
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    xaxis: { categories: @json($chartLabels) },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                return 'R$ ' + val.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            },
                        },
                    },
                    fill: { opacity: 1 },
                    colors: ['#F1416C', '#50CD89', '#009EF7'],
                    legend: { position: 'top' },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return 'R$ ' + val.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            },
                        },
                    },
                };

                new ApexCharts(el, options).render();
            });
        </script>
        @endpush
    @endif

</x-base-layout>
