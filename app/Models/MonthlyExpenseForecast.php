<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyExpenseForecast extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $fillable = [
        'year',
        'month',
        'expense_category_id',
        'expected_amount',
    ];

    protected $casts = [
        'year'            => 'integer',
        'month'           => 'integer',
        'expense_category_id' => 'integer',
        'expected_amount' => 'decimal:2',
    ];

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
