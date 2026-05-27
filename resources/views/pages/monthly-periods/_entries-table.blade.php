@php
    use App\Models\MonthlyEntry;
    use App\Models\MonthlyPeriod;
@endphp

<div class="table-responsive">
    <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-4">
        <thead>
            <tr class="fw-bolder text-muted">
                <th>{{ __('Data') }}</th>
                <th>{{ __('Categoria') }}</th>
                <th>{{ __('Descrição') }}</th>
                <th class="text-end">{{ __('Valor') }}</th>
                @if (!$monthlyPeriod->is_closed)
                    <th class="text-end" style="min-width: 90px;">{{ __('Ações') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($entries as $entry)
                <tr>
                    <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                    <td class="fw-bold">{{ $entry->category_name }}</td>
                    <td class="text-muted">{{ $entry->description ?: '—' }}</td>
                    <td class="text-end fw-bold {{ $type === 'expense' ? 'text-danger' : 'text-success' }}">
                        {{ MonthlyPeriod::formatMoney((float) $entry->amount) }}
                    </td>
                    @if (!$monthlyPeriod->is_closed)
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button"
                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                                        title="{{ __('Editar') }}"
                                        data-edit-entry
                                        data-action="{{ route('monthly-periods.entries.update', [$monthlyPeriod, $entry]) }}"
                                        data-type="{{ $entry->type }}"
                                        data-expense-category-id="{{ $entry->expense_category_id }}"
                                        data-income-category-id="{{ $entry->income_category_id }}"
                                        data-amount="{{ number_format((float) $entry->amount, 2, '.', '') }}"
                                        data-entry-date="{{ $entry->entry_date->format('Y-m-d') }}"
                                        data-description="{{ e($entry->description ?? '') }}">
                                    {!! theme()->getSvgIcon('icons/duotune/art/art005.svg', 'svg-icon-3') !!}
                                </button>
                                <form method="POST" action="{{ route('monthly-periods.entries.destroy', [$monthlyPeriod, $entry]) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('{{ __('Remover este lançamento?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="{{ __('Excluir') }}">
                                        {!! theme()->getSvgIcon('icons/duotune/general/gen027.svg', 'svg-icon-3') !!}
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $monthlyPeriod->is_closed ? 4 : 5 }}" class="text-center text-muted py-8">
                        {{ __('Nenhum lançamento neste bloco.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($entries->isNotEmpty())
            <tfoot>
                <tr class="fw-bolder border-top">
                    <td colspan="3" class="text-end">{{ __('Subtotal') }}</td>
                    <td class="text-end {{ $type === 'expense' ? 'text-danger' : 'text-success' }}">
                        {{ MonthlyPeriod::formatMoney((float) $entries->sum('amount')) }}
                    </td>
                    @if (!$monthlyPeriod->is_closed)
                        <td></td>
                    @endif
                </tr>
            </tfoot>
        @endif
    </table>
</div>
