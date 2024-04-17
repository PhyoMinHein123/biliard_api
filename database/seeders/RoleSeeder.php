<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Helpers\Enum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = collect(Enum::make(RoleEnum::class)->values())->map(function ($role) {
            try {

                $createRole = Role::create([
                    'name' => $role,
                    'guard_name' => 'api',
                ]);

            } catch (Exception $e) {
                info($e);
            }
        });
    }
}
