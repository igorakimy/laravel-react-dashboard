<?php

namespace App\Data\User;

use App\Data\Transformers\HashableTransformer;
use App\Http\Requests\Api\User\UpdateUserRequest;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class UserUpdateData extends Data
{
    public function __construct(
        public string $name,
        public string $email,

        #[WithTransformer(HashableTransformer::class)]
        public ?string $password
    ) {}

    public static function fromRequest(UpdateUserRequest $request): self
    {
        return new self(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password')
        );
    }
}
