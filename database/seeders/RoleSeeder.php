<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->upsert(
            collect(UserRole::cases())
                ->map(fn (UserRole $role) => [
                    'name' => $role->value,
                    'guard_name' => 'api',
                ])
                ->all(),
            ['name', 'guard_name']
        );
    }
}
