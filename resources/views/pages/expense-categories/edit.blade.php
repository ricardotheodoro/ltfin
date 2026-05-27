<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">
                    {{ __('Editar categoria') }} <span class="text-muted fw-normal fs-5">#{{ $expenseCategory->id }}</span>
                </h2>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form method="POST" action="{{ route('expense-categories.update', $expenseCategory) }}" novalidate>
                @method('PUT')
                @include('pages.expense-categories._form', [
                    'submitLabel' => __('Salvar alterações'),
                ])
            </form>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

</x-base-layout>
