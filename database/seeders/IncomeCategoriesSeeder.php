<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use Illuminate\Database\Seeder;

class IncomeCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Salário', 'description' => 'Rendimento principal'],
            ['name' => 'Freelance', 'description' => 'Projetos e serviços avulsos'],
            ['name' => 'Rendimentos', 'description' => 'Investimentos e aplicações'],
            ['name' => 'Outros recebimentos', 'description' => 'Entradas diversas'],
        ];

        foreach ($categories as $category) {
            IncomeCategory::query()->updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
