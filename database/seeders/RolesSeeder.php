<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->data();

        foreach ($data as $value) {
            Role::query()->firstOrCreate(
                ['name' => $value['name'], 'guard_name' => 'web'],
                ['name' => $value['name'], 'guard_name' => 'web']
            );
        }
    }

    public function data()
    {
        return [
            ['name' => 'admin'],
            ['name' => 'editor'],
        ];
    }
}
