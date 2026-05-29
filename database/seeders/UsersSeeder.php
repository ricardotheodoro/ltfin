<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @deprecated Use LocalDevelopmentSeeder ou AdminUserSeeder.
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
    }
}
