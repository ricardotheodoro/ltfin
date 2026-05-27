<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyPeriod extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $fillable = [
        'year',
        'month',
        'notes',
        'is_closed',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(MonthlyEntry::class);
    }

    public function expenseEntries(): HasMany
    {
        return $this->hasMany(MonthlyEntry::class)->where('type', MonthlyEntry::TYPE_EXPENSE);
    }

    public function incomeEntries(): HasMany
    {
        return $this->hasMany(MonthlyEntry::class)->where('type', MonthlyEntry::TYPE_INCOME);
    }

    public function getLabelAttribute(): string
    {
        $months = [
            1  => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5  => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9  => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        return ($months[$this->month] ?? $this->month).'/'.$this->year;
    }

    public function getTotalExpensesAttribute(): float
    {
        return (float) $this->entries()->where('type', MonthlyEntry::TYPE_EXPENSE)->sum('amount');
    }

    public function getTotalIncomeAttribute(): float
    {
        return (float) $this->entries()->where('type', MonthlyEntry::TYPE_INCOME)->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_income - $this->total_expenses;
    }

    public static function formatMoney(float $amount): string
    {
        return 'R$ '.number_format($amount, 2, ',', '.');
    }

    /**
     * Formata valor para exibição em campos de formulário (sem prefixo R$).
     */
    public static function formatMoneyInput($amount): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        return number_format((float) $amount, 2, ',', '.');
    }

    /**
     * Converte entrada do usuário (pt-BR ou decimal) para string numérica com ponto.
     */
    public static function parseMoneyInput($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return number_format((float) $value, 2, '.', '');
        }

        $value = trim((string) $value);
        $value = preg_replace('/[R$\s]/u', '', $value);

        if ($value === '') {
            return null;
        }

        if (strpos($value, ',') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        if (!is_numeric($value)) {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }
}
