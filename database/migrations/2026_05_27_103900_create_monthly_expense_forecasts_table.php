<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyExpenseForecastsTable extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_expense_forecasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->foreignId('expense_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('expected_amount', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['year', 'month', 'expense_category_id'], 'monthly_expense_forecasts_period_category_unique');
            $table->index(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_expense_forecasts');
    }
}
