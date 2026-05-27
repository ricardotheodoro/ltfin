<x-base-layout>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">{{ __('Lançamentos Mensais') }}</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('monthly-periods.create') }}" class="btn btn-primary">
                    {!! theme()->getSvgIcon('icons/duotune/arrows/arr075.svg', 'svg-icon-2') !!}
                    {{ __('Novo mês') }}
                </a>
            </div>
        </div>

        <div class="card-body pt-6">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center mb-6">
                    {!! theme()->getSvgIcon('icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-success me-3') !!}
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @include('pages.monthly-periods._table')
        </div>
    </div>

</x-base-layout>
