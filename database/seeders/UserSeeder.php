<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionsList;
use App\Enums\Role as RolesList;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = RolesList::list();
        $permissions = PermissionsList::list();

        // create permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission->value,
                'visible_name' => $permission->name(),
                'guard_name' => 'api',
            ]);
        }

        // create roles
        $rolesList = [];
        foreach ($roles as $role) {
            /** @var Role $createdRole */
            $createdRole = Role::create([
                'name' => $role->value,
            ]);
            if ($role === RolesList::SUPER_ADMIN) {
                $createdRole->givePermissionTo(PermissionsList::CAN_DO_ANYTHING);
            } else {
                $existRoles = [];
                for ($i = 0; $i < 3; $i++) {
                    $index = $this->generateRandomIndex(1, count($permissions) - 1, $existRoles);
                    $createdRole->givePermissionTo(
                        $permissions[$index]
                    );
                    $existRoles[] = $index;
                }
            }
            $rolesList[] = $createdRole;
        }

        for($i = 0; $i < 3000; $i++) {
            /** @var User $user */
            $user = User::factory(1)->create()->first();
            $role = $rolesList[random_int(0, count($rolesList) - 1)];
            $user->assignRole(RolesList::from($role->name));
        }

    }

    /**
     * Generate random index.
     *
     * @param  int  $min
     * @param  int  $max
     * @param  array  $indexes
     *
     * @return int
     * @throws Exception
     */
    private function generateRandomIndex(int $min, int $max, array $indexes): int
    {
        $index = random_int($min, $max);
        while (in_array($index, $indexes)) {
            $index = random_int($min, $max);
        }

        return $index;
    }
}
