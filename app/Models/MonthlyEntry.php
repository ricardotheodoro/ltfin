<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyEntry extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    public const TYPE_EXPENSE = 'expense';
    public const TYPE_INCOME = 'income';

    protected $fillable = [
        'monthly_period_id',
        'type',
        'expense_category_id',
        'income_category_id',
        'amount',
        'description',
        'entry_date',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function monthlyPeriod(): BelongsTo
    {
        return $this->belongsTo(MonthlyPeriod::class);
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class);
    }

    public function getCategoryNameAttribute(): string
    {
        if ($this->type === self::TYPE_EXPENSE) {
            return $this->expenseCategory?->name ?? '—';
        }

        return $this->incomeCategory?->name ?? '—';
    }
}
