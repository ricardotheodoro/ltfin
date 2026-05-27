<x-base-layout>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bolder">{{ __('Novo lançamento mensal') }}</h2>
            </div>
        </div>

        <div class="card-body pt-6">
            <p class="text-muted mb-8">
                {{ __('Selecione o mês/ano para abrir o balanço. Em seguida você poderá lançar gastos e recebíveis.') }}
            </p>

            <form method="POST" action="{{ route('monthly-periods.store') }}" novalidate>
                @csrf

                <div class="row mb-7">
                    <label for="month" class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('Mês') }}</label>
                    <div class="col-lg-9">
                        <select id="month" name="month" class="form-select form-select-lg form-select-solid @error('month') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione...') }}</option>
                            @foreach ($monthOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('month', (int) date('n')) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('month')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-7">
                    <label for="year" class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('Ano') }}</label>
                    <div class="col-lg-9">
                        <select id="year" name="year" class="form-select form-select-lg form-select-solid @error('year') is-invalid @enderror" required>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ old('year', (int) date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('year')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-10">
                    <label for="notes" class="col-lg-3 col-form-label fw-bold fs-6">{{ __('Observações') }}</label>
                    <div class="col-lg-9">
                        <textarea id="notes" name="notes" rows="3" class="form-control form-control-lg form-control-solid @error('notes') is-invalid @enderror" maxlength="1000">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('monthly-periods.index') }}" class="btn btn-light">{{ __('Cancelar') }}</a>
                    <button type="submit" class="btn btn-primary">
                        @include('partials.general._button-indicator', ['label' => __('Abrir mês')])
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-base-layout>
