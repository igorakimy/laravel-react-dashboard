<?php

namespace App\Models;

use App\Models\Traits\HasPermissions;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\RefreshesPermissionCache;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Role extends Model implements RoleContract
{
    use HasFactory;
    use HasPermissions;
    use RefreshesPermissionCache;

    protected $guarded = [];

    /**
     * Constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    /**
     * Get the table name.
     *
     * @return Repository|Application|mixed|string
     */
    public function getTable(): mixed
    {
        return config('permission.table_names.roles', parent::getTable());
    }

    /**
     * Create new role.
     *
     * @param  array  $attributes
     *
     * @return Builder|EloquentModel
     */
    public static function create(array $attributes = []): EloquentModel|Builder
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = [
            'name' => $attributes['name'],
            'guard_name' => $attributes['guard_name']
        ];
        if (PermissionRegistrar::$teams) {
            if (array_key_exists(PermissionRegistrar::$teamsKey, $attributes)) {
                $params[PermissionRegistrar::$teamsKey] = $attributes[PermissionRegistrar::$teamsKey];
            } else {
                $attributes[PermissionRegistrar::$teamsKey] = getPermissionsTeamId();
            }
        }
        if (static::findByParam($params)) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            config('permission.table_names.model_has_roles'),
            PermissionRegistrar::$pivotRole,
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param  string  $name
     * @param  string|null  $guardName
     *
     * @return RoleContract
     *
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        /** @var RoleContract $role */
        $role = static::findByParam([
            'name' => $name,
            'guard_name' => $guardName
        ]);

        if (! $role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    /**
     * Find a role by its id (and optionally guardName).
     *
     * @param  int  $id
     * @param  string|null  $guardName
     *
     * @return RoleContract
     */
    public static function findById(int $id, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        /** @var RoleContract $role */
        $role = static::findByParam([
            (new static())->getKeyName() => $id,
            'guard_name' => $guardName
        ]);

        if (! $role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param  string|null  $guardName
     *
     * @return RoleContract
     */
    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        /** @var RoleContract $role */
        $role = static::findByParam([
            'name' => $name,
            'guard_name' => $guardName
        ]);

        if (! $role) {
            return static::query()->create(
                ['name' => $name, 'guard_name' => $guardName]
                + (PermissionRegistrar::$teams ? [PermissionRegistrar::$teamsKey => getPermissionsTeamId()] : []));
        }

        return $role;
    }

    /**
     * Find role by params.
     *
     * @param  array  $params
     *
     * @return Builder|EloquentModel|null
     */
    protected static function findByParam(array $params = []): EloquentModel|Builder|null
    {
        $query = static::query();

        if (PermissionRegistrar::$teams) {
            $query->where(function ($q) use ($params) {
                $q->whereNull(PermissionRegistrar::$teamsKey)
                  ->orWhere(
                      PermissionRegistrar::$teamsKey,
                      $params[PermissionRegistrar::$teamsKey] ?? getPermissionsTeamId()
                  );
            });
            unset($params[PermissionRegistrar::$teamsKey]);
        }

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  string|Permission  $permission
     *
     * @throws GuardDoesNotMatch
     */
    public function hasPermissionTo($permission): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());
        }

        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (! $this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        }

        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }
}
