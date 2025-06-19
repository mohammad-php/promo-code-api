<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdmin();
        $this->createUser();
    }

    /**
     * @return void
     */
    private function createAdmin(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@promo.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        $role = Role::whereName(UserRole::Admin->value)->first();

//        dd($role);

        $admin->syncRoles([$role->name]);
    }

    /**
     * @return void
     */
    private function createUser(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'user@promo.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password'),
            ]
        );

        $role = Role::whereName(UserRole::User->value)->first();

        $user->syncRoles([$role->name]);
    }
}
