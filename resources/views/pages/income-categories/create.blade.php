<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">{{ __('Nova categoria de recebimento') }}</h2>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form method="POST" action="{{ route('income-categories.store') }}" novalidate>
                @include('pages.income-categories._form', [
                    'submitLabel' => __('Criar categoria'),
                ])
            </form>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

</x-base-layout>
