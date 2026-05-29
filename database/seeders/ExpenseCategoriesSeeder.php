<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Aluguel', 'description' => 'Despesas com locação'],
            ['name' => 'Energia', 'description' => 'Conta de luz e gás'],
            ['name' => 'Alimentação', 'description' => 'Supermercado e refeições'],
            ['name' => 'Transporte', 'description' => 'Combustível, transporte público e apps'],
            ['name' => 'Salários', 'description' => 'Folha de pagamento'],
            ['name' => 'Internet e telefone', 'description' => 'Serviços de conectividade'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::query()->updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
