@csrf

<!--begin::Input group: nome-->
<div class="row mb-7">
    <label for="name" class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('Nome') }}</label>
    <div class="col-lg-9">
        <input
            id="name"
            name="name"
            type="text"
            class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror"
            value="{{ old('name', $incomeCategory->name) }}"
            maxlength="120"
            required
            autofocus
        />
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>
<!--end::Input group: nome-->

<!--begin::Input group: descrição-->
<div class="row mb-7">
    <label for="description" class="col-lg-3 col-form-label fw-bold fs-6">{{ __('Descrição') }}</label>
    <div class="col-lg-9">
        <textarea
            id="description"
            name="description"
            rows="4"
            class="form-control form-control-lg form-control-solid @error('description') is-invalid @enderror"
            maxlength="1000"
        >{{ old('description', $incomeCategory->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">{{ __('Texto livre para descrever a categoria (opcional).') }}</div>
    </div>
</div>
<!--end::Input group: descrição-->

<!--begin::Input group: ativo-->
<div class="row mb-10">
    <label class="col-lg-3 col-form-label fw-bold fs-6">{{ __('Status') }}</label>
    <div class="col-lg-9 d-flex align-items-center">
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input type="hidden" name="is_active" value="0">
            <input
                id="is_active"
                name="is_active"
                type="checkbox"
                value="1"
                class="form-check-input"
                {{ old('is_active', $incomeCategory->is_active ?? true) ? 'checked' : '' }}
            />
            <label for="is_active" class="form-check-label fw-bold text-gray-700">
                {{ __('Ativo') }}
            </label>
        </div>
    </div>
</div>
<!--end::Input group: ativo-->

<!--begin::Actions-->
<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('income-categories.index') }}" class="btn btn-light">
        {{ __('Cancelar') }}
    </a>
    <button type="submit" class="btn btn-primary">
        @include('partials.general._button-indicator', ['label' => $submitLabel ?? __('Salvar')])
    </button>
</div>
<!--end::Actions-->
