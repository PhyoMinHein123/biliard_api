<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Helpers\Enum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Enum::make(PermissionEnum::class)->values();

        $roles = Role::all()->map(function ($role) use ($permissions) {

            if ($role->name === RoleEnum::SUPER_ADMIN->value) {

                $role->syncPermissions($permissions);
            }

            if ($role->name === RoleEnum::MANAGER->value) {

                $role->syncPermissions([
                    'USER_INDEX',
                    'USER_STORE',
                ]);
            }

            if ($role->name === RoleEnum::WAITER->value) {

                $role->syncPermissions([
                    'TABLE_NUMBER_INDEX',
                    'ORDER_INDEX',
                    'ORDER_UPDATE',
                    'ORDER_STORE',
                ]);
            }
        });
    }
}
