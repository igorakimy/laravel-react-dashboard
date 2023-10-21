<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = PermissionEnum::cases();

        $exisingPermissions = Permission::all()->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            if ( ! in_array($permission->value, $exisingPermissions)) {
                Permission::create([
                    'name' => $permission->value,
                    'display_name' => $permission->name(),
                    'guard_name' => 'web',
                ]);
            } else {
                $existingPermission = Permission::query()->where('name', $permission->value)->first();
                if ($existingPermission->display_name != $permission->name()) {
                    $existingPermission->update([
                        'display_name' => $permission->name()
                    ]);
                }
            }
        }
    }
}
