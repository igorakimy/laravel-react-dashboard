<?php

namespace App\Data\User;

use App\Data\Role\RoleData;
use App\Data\Transformers\HashableTransformer;
use App\Enums\UserStatus;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class UserUpdateData extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public UserStatus $status,

        #[WithTransformer(HashableTransformer::class)]
        public ?string $password,

        #[DataCollectionOf(RoleData::class)]
        public DataCollection $roles,
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
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            email: $request->input('email'),
            status: UserStatus::from($request->input('status')),
            password: $request->input('password'),
            roles: RoleData::collection(
                Role::query()
                    ->with('permissions')
                    ->findMany($request->input('roles'))
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'status' => ['required', new Enum(UserStatus::class)],
            'email' => [
                'required',
                'email',
                'max:255',
                new Unique(
                    table: 'users',
                    column: 'email',
                    ignore: new RouteParameterReference('user', 'id')
                )
            ],
            'password' => [
                'confirmed',
                'max:255',
                Password::min(8)
                        ->letters()
                        ->numbers()
                        ->symbols(),
            ],
        ];
    }
}
