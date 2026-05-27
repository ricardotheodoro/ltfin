@php
    use App\Models\MonthlyPeriod;
@endphp

<x-base-layout>

    <div class="d-flex flex-wrap flex-stack mb-6">
        <div>
            <a href="{{ route('monthly-periods.index') }}" class="text-muted text-hover-primary fs-7 fw-bold mb-2 d-inline-block">
                ← {{ __('Voltar para lançamentos mensais') }}
            </a>
            <h1 class="fw-bolder text-gray-900 mb-0">{{ $monthlyPeriod->label }}</h1>
            @if ($monthlyPeriod->notes)
                <p class="text-muted fs-6 mb-0 mt-2">{{ $monthlyPeriod->notes }}</p>
            @endif
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if ($monthlyPeriod->is_closed)
                <span class="badge badge-light-warning fs-7">{{ __('Mês fechado') }}</span>
                <form method="POST" action="{{ route('monthly-periods.reopen', $monthlyPeriod) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-light-primary">{{ __('Reabrir mês') }}</button>
                </form>
            @else
                <span class="badge badge-light-primary fs-7">{{ __('Mês aberto') }}</span>
                <form method="POST" action="{{ route('monthly-periods.close', $monthlyPeriod) }}" class="d-inline"
                      onsubmit="return confirm('{{ __('Fechar o mês? Não será possível alterar lançamentos até reabrir.') }}');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-light-warning">{{ __('Fechar mês') }}</button>
                </form>
            @endif

            <a href="{{ route('monthly-periods.export.csv', $monthlyPeriod) }}" class="btn btn-sm btn-light">
                {!! theme()->getSvgIcon('icons/duotune/files/fil021.svg', 'svg-icon-3') !!}
                {{ __('Exportar Excel') }}
            </a>
            <a href="{{ route('monthly-periods.export.pdf', $monthlyPeriod) }}" class="btn btn-sm btn-light" target="_blank">
                {!! theme()->getSvgIcon('icons/duotune/files/fil003.svg', 'svg-icon-3') !!}
                {{ __('Exportar PDF') }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-6">
            {!! theme()->getSvgIcon('icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-success me-3') !!}
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-6">
            {!! theme()->getSvgIcon('icons/duotune/general/gen040.svg', 'svg-icon-2x svg-icon-danger me-3') !!}
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- Resumo do balanço --}}
    <div class="row g-5 g-xl-8 mb-8">
        <div class="col-md-4">
            <div class="card card-flush h-100 border border-danger border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Total de gastos') }}</span>
                    <span class="fs-2hx fw-bolder text-danger lh-1 ls-n2 mt-2">
                        {{ MonthlyPeriod::formatMoney($monthlyPeriod->total_expenses) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush h-100 border border-success border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Total de recebíveis') }}</span>
                    <span class="fs-2hx fw-bolder text-success lh-1 ls-n2 mt-2">
                        {{ MonthlyPeriod::formatMoney($monthlyPeriod->total_income) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @php $balance = $monthlyPeriod->balance; @endphp
            <div class="card card-flush h-100 border border-primary border-dashed">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="text-gray-500 fw-semibold fs-7">{{ __('Saldo do mês') }}</span>
                    <span class="fs-2hx fw-bolder lh-1 ls-n2 mt-2 {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ MonthlyPeriod::formatMoney($balance) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-8">
        {{-- Recebíveis --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bolder text-success">{{ __('Recebíveis') }}</h3>
                </div>
                <div class="card-body pt-2">
                    @if (!$monthlyPeriod->is_closed)
                        @include('pages.monthly-periods._entry-form', ['type' => 'income'])
                    @endif

                    @include('pages.monthly-periods._entries-table', [
                        'entries' => $incomeEntries,
                        'type'    => 'income',
                    ])
                </div>
            </div>
        </div>

        {{-- Gastos --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title fw-bolder text-danger">{{ __('Gastos') }}</h3>
                </div>
                <div class="card-body pt-2">
                    @if (!$monthlyPeriod->is_closed)
                        @include('pages.monthly-periods._entry-form', ['type' => 'expense'])
                    @endif

                    @include('pages.monthly-periods._entries-table', [
                        'entries' => $expenseEntries,
                        'type'    => 'expense',
                    ])
                </div>
            </div>
        </div>
    </div>

    @if (!$monthlyPeriod->is_closed)
        @include('pages.monthly-periods._entry-edit-modal')
        @include('pages.monthly-periods._amount-field-scripts')
    @endif

</x-base-layout>
