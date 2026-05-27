@php
    $type = $type ?? 'expense';
    $isExpense = $type === 'expense';
@endphp

<form method="POST" action="{{ route('monthly-periods.entries.store', $monthlyPeriod) }}" class="mb-6">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="row g-4 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-bold required">{{ $isExpense ? __('Categoria de gasto') : __('Categoria de recebimento') }}</label>
            <select name="{{ $isExpense ? 'expense_category_id' : 'income_category_id' }}"
                    class="form-select form-select-solid @error($isExpense ? 'expense_category_id' : 'income_category_id') is-invalid @enderror"
                    required>
                <option value="">{{ __('Selecione...') }}</option>
                @if ($isExpense)
                    @foreach ($expenseCategories as $category)
                        <option value="{{ $category->id }}" {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                @else
                    @foreach ($incomeCategories as $category)
                        <option value="{{ $category->id }}" {{ old('income_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                @endif
            </select>
            @error($isExpense ? 'expense_category_id' : 'income_category_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold required">{{ __('Valor') }}</label>
            @include('pages.monthly-periods._amount-field', [
                'name'     => 'amount',
                'invalid'  => $errors->has('amount'),
                'required' => true,
            ])
            @error('amount')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold required">{{ __('Data') }}</label>
            <input type="date" name="entry_date" class="form-control form-control-solid @error('entry_date') is-invalid @enderror"
                   value="{{ old('entry_date', $defaultEntryDate) }}" required />
            @error('entry_date')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">{{ __('Descrição') }}</label>
            <input type="text" name="description" maxlength="500" class="form-control form-control-solid"
                   value="{{ old('description') }}" placeholder="{{ __('Opcional') }}" />
        </div>

        <div class="col-md-auto">
            <button type="submit" class="btn {{ $isExpense ? 'btn-danger' : 'btn-success' }}">
                {!! theme()->getSvgIcon('icons/duotune/arrows/arr075.svg', 'svg-icon-2') !!}
                {{ $isExpense ? __('Lançar gasto') : __('Lançar recebível') }}
            </button>
        </div>
    </div>
</form>
