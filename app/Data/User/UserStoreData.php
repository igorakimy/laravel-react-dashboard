<?php

namespace App\Data\User;

use App\Data\Role\RoleData;
use App\Data\Transformers\HashableTransformer;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use App\Models\Role;

class UserStoreData extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public UserStatus $status,

        #[WithTransformer(HashableTransformer::class)]
        public string $password,

        #[DataCollectionOf(RoleData::class)]
        public DataCollection $roles
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
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            email: $request->input('email'),
            status: UserStatus::from($request->input('status')),
            password: $request->input('password'),
            roles: RoleData::collection(
                Role::query()
                    ->with('permissions')
                    ->findMany($request->input('roles'))),
        );
    }

    /**
     * @return array[]
     */
    public static function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email', 'max:150'],
            'password' => ['required', 'min:8', 'max:64', 'confirmed'],
            'status' => ['required', new Enum(UserStatus::class)],
            'roles' => ['required', 'array'],
        ];
    }
}
