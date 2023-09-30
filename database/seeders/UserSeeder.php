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
     */
    public function run(): void
    {
         app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = RolesList::cases();
        $permissions = PermissionsList::cases();

        // create permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission->value,
                'display_name' => $permission->name(),
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
                $rolesList[] = $createdRole;
                continue;
            } else {
                $existRoles = [];
                for ($i = 0; $i < 3; $i++) {
                    $index = count($permissions) > 1
                        ? $this->generateRandomIndex(1, count($permissions) - 1, $existRoles)
                        : 0;
                    $createdRole->givePermissionTo(
                        $permissions[$index]
                    );
                    $existRoles[] = $index;
                }
            }
            $rolesList[] = $createdRole;
        }

        $usersAmount = 2000;

        for($i = 0; $i < $usersAmount; $i++) {
            /** @var User $user */
            $user = User::factory(1)->create()->first();
            $role = $rolesList[rand(0, count($rolesList) - 1)];
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
     */
    private function generateRandomIndex(int $min, int $max, array $indexes): int
    {
        $index = rand($min, $max);
        while (in_array($index, $indexes)) {
            $index = rand($min, $max);
        }

        return $index;
    }
}
