<?php

namespace App\Data\Role;

use App\Data\Permission\PermissionData;
use App\Models\Permission;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class RoleStoreData extends Data
{
    public function __construct(
        public string $name,

        #[DataCollectionOf(PermissionData::class)]
        public DataCollection $permissions
    ) {}

    /**
     * @param  Request  $request
     *
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            name: $request->input('name'),
            permissions: PermissionData::collection(
                Permission::query()
                    ->findMany($request->input('permissions'))),
        );
    }

    /**
     * @return array[]
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', 'unique:roles,name'],
            'permissions' => ['required', 'array'],
        ];
    }
}
