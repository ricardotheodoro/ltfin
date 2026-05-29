<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Dados completos para desenvolvimento local e restauração pós-testes.
 *
 * Uso: php artisan db:restore-dev
 *      php artisan migrate:fresh --seed
 */
class LocalDevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            AdminUserSeeder::class,
            ExpenseCategoriesSeeder::class,
            IncomeCategoriesSeeder::class,
            MonthlyFinanceSeeder::class,
        ]);
    }
}
