<?php

namespace App\Data\Role;

use App\Data\Permission\PermissionData;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class RoleUpdateData extends Data
{
    public function __construct(
        public string $name,

        #[DataCollectionOf(PermissionData::class)]
        public DataCollection $permissions,
    ) {}

    /**
     * @param  Request  $request
     *
     * @return self
     * @throws Exception
     */
    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            name: $request->input('name'),
            permissions: PermissionData::collection(
                Permission::query()
                    ->findMany($request->input('permissions'))
                    ->toArray()
            )
        );
    }

    /**
     * @throws Exception
     */
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                new Unique(
                    table: 'roles',
                    column: 'name',
                    ignore: new RouteParameterReference('role', 'id')
                )
            ],
            'permissions' => [
                'required',
                'array',
            ],
        ];
    }
}
