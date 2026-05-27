<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">{{ __('Categorias de Gastos') }}</h2>
            </div>

            <div class="card-toolbar">
                <a href="{{ route('expense-categories.create') }}" class="btn btn-primary">
                    {!! theme()->getSvgIcon('icons/duotune/arrows/arr075.svg', 'svg-icon-2') !!}
                    {{ __('Nova categoria') }}
                </a>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-6">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center mb-6">
                    {!! theme()->getSvgIcon('icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-success me-3') !!}
                    <div class="d-flex flex-column">{{ session('success') }}</div>
                </div>
            @endif

            @include('pages.expense-categories._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

</x-base-layout>
