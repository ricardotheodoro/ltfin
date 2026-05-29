<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::query()->where('name', 'admin')->first();

        if ($adminRole !== null) {
            $adminRole->syncPermissions(Permission::all());
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@ltfin.test'],
            [
                'first_name'        => 'Admin',
                'last_name'         => 'LTFin',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $demo = User::query()->updateOrCreate(
            ['email' => 'demo@demo.com'],
            [
                'first_name'        => 'Demo',
                'last_name'         => 'User',
                'password'          => Hash::make('demo'),
                'email_verified_at' => now(),
            ]
        );

        $owner = User::query()->updateOrCreate(
            ['email' => 'rjtheodoro@gmail.com'],
            [
                'first_name'        => 'Ricardo',
                'last_name'         => 'Theodoro',
                'password'          => Hash::make('1234'),
                'email_verified_at' => now(),
            ]
        );

        if ($adminRole !== null) {
            $admin->syncRoles([$adminRole]);
            $owner->syncRoles([$adminRole]);
        }

        $this->seedUserInfo($admin, [
            'company'  => 'LT TechFin',
            'phone'    => '(11) 99999-0001',
            'website'  => 'https://ltfin.test',
            'language' => 'pt',
            'country'  => 'br',
        ]);

        $this->seedUserInfo($demo, [
            'company'  => 'Demo Company',
            'phone'    => '(11) 99999-0002',
            'website'  => 'https://demo.test',
            'language' => 'pt',
            'country'  => 'br',
        ]);

        $this->seedUserInfo($owner, [
            'company'  => 'LT TechFin',
            'phone'    => '',
            'website'  => '',
            'language' => 'pt',
            'country'  => 'br',
        ]);
    }

    private function seedUserInfo(User $user, array $attributes): void
    {
        UserInfo::query()->updateOrCreate(
            ['user_id' => $user->id],
            $attributes
        );
    }
}
