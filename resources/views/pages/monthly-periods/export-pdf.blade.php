@php
    use App\Models\MonthlyPeriod;
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ __('Balanço') }} — {{ $monthlyPeriod->label }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #181c32; margin: 24px; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .muted { color: #7e8299; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #e4e6ef; padding: 8px; text-align: left; }
        th { background: #f5f8fa; }
        .text-end { text-align: right; }
        .danger { color: #f1416c; }
        .success { color: #50cd89; }
        .summary { margin-top: 16px; }
        .summary td { font-weight: bold; border: none; }
        .no-print { margin-bottom: 16px; }
        @media print {
            .no-print { display: none; }
            body { margin: 12px; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" onclick="window.print()">{{ __('Imprimir / Salvar como PDF') }}</button>
        <a href="{{ route('monthly-periods.show', $monthlyPeriod) }}">{{ __('Voltar') }}</a>
    </div>

    <h1>{{ __('Balanço mensal') }} — {{ $monthlyPeriod->label }}</h1>
    <p class="muted">{{ __('Gerado em') }} {{ now()->format('d/m/Y H:i') }}</p>

    <h2 class="danger">{{ __('Gastos') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('Data') }}</th>
                <th>{{ __('Categoria') }}</th>
                <th>{{ __('Descrição') }}</th>
                <th class="text-end">{{ __('Valor') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenseEntries as $entry)
                <tr>
                    <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                    <td>{{ $entry->category_name }}</td>
                    <td>{{ $entry->description ?: '—' }}</td>
                    <td class="text-end">{{ MonthlyPeriod::formatMoney((float) $entry->amount) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('Nenhum gasto.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="success">{{ __('Recebíveis') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('Data') }}</th>
                <th>{{ __('Categoria') }}</th>
                <th>{{ __('Descrição') }}</th>
                <th class="text-end">{{ __('Valor') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($incomeEntries as $entry)
                <tr>
                    <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                    <td>{{ $entry->category_name }}</td>
                    <td>{{ $entry->description ?: '—' }}</td>
                    <td class="text-end">{{ MonthlyPeriod::formatMoney((float) $entry->amount) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('Nenhum recebível.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>{{ __('Total de gastos') }}</td>
            <td class="text-end danger">{{ MonthlyPeriod::formatMoney($monthlyPeriod->total_expenses) }}</td>
        </tr>
        <tr>
            <td>{{ __('Total de recebíveis') }}</td>
            <td class="text-end success">{{ MonthlyPeriod::formatMoney($monthlyPeriod->total_income) }}</td>
        </tr>
        <tr>
            <td>{{ __('Saldo do mês') }}</td>
            <td class="text-end">{{ MonthlyPeriod::formatMoney($monthlyPeriod->balance) }}</td>
        </tr>
    </table>
</body>
</html>
