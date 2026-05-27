@php
    use App\Models\MonthlyPeriod;

    $name = $name ?? 'amount';
    $inputId = $id ?? $name;
    $displayValue = old($name, $value ?? '');
    if ($displayValue !== '' && $displayValue !== null && is_numeric($displayValue)) {
        $displayValue = MonthlyPeriod::formatMoneyInput($displayValue);
    }
@endphp

<div class="input-group input-group-solid @if(!empty($invalid)) has-validation @endif">
    <span class="input-group-text fw-semibold text-gray-600">R$</span>
    <input type="text"
           name="{{ $name }}"
           id="{{ $inputId }}"
           class="form-control js-money-amount text-end @if(!empty($invalid)) is-invalid @endif"
           value="{{ $displayValue }}"
           inputmode="numeric"
           placeholder="0,00"
           autocomplete="off"
           @if(!empty($required)) required @endif
           aria-label="{{ __('Valor') }}">
</div>
