<?php

namespace App\Models;

use App\Models\Traits\HasRoles;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\RefreshesPermissionCache;
use App\Enums\Permission as PermissionEnum;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $guard_name
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Permission extends Model implements PermissionContract
{
    use HasFactory;
    use HasRoles;
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
     * Get table name.
     *
     * @return Repository|Application|mixed|string
     */
    public function getTable(): mixed
    {
        return config('permission.table_names.permissions', parent::getTable());
    }

    /**
     * Create a new Permission instance.
     *
     * @param  array  $attributes
     *
     * @return Builder|Model
     */
    public static function create(array $attributes = []): Model|Builder
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'name' => $attributes['name'],
            'display_name' => $attributes['display_name'],
            'guard_name' => $attributes['guard_name']
        ]);

        if ($permission) {
            throw PermissionAlreadyExists::create(
                $attributes['name'],
                $attributes['guard_name']
            );
        }

        return static::query()->create($attributes);
    }

    /**
     * A permission can be applied to roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            PermissionRegistrar::$pivotRole
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            config('permission.table_names.model_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a permission by its name (and optionally guardName).
     *
     * @param  string|null  $guardName
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'name' => $name,
            'guard_name' => $guardName
        ]);

        if (! $permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }

        return $permission;
    }

    /**
     * Find a permission by its id (and optionally guardName).
     *
     * @param  string|null  $guardName
     *
     * @throws PermissionDoesNotExist
     */
    public static function findById(int $id, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            (new static())->getKeyName() => $id,
            'guard_name' => $guardName
        ]);

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id, $guardName);
        }

        return $permission;
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param $guardName
     *
     * @return PermissionContract
     */
    public static function findOrCreate(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'name' => $name,
            'guard_name' => $guardName
        ]);

        if (! $permission) {
            return static::query()->create([
                'name' => $name,
                'display_name' => PermissionEnum::from($name)->name(),
                'guard_name' => $guardName
            ]);
        }

        return $permission;
    }

    /**
     * Get the current cached permissions.
     *
     * @param  array  $params
     * @param  bool  $onlyOne
     *
     * @return Collection
     */
    protected static function getPermissions(array $params = [], bool $onlyOne = false): Collection
    {
        return app(PermissionRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params, $onlyOne);
    }

    /**
     * Get the current cached first permission.
     *
     * @param  array  $params
     *
     * @return PermissionContract|null
     */
    protected static function getPermission(array $params = []): ?PermissionContract
    {
        return static::getPermissions($params, true)->first();
    }
}
