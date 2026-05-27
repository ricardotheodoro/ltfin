<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyEntriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_period_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['expense', 'income']);
            $table->foreignId('expense_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('income_category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('description', 500)->nullable();
            $table->date('entry_date');
            $table->timestamps();

            $table->index(['monthly_period_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_entries');
    }
}
