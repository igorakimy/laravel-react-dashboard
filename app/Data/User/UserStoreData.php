<?php

namespace App\Data\User;

use App\Data\Transformers\HashableTransformer;
use App\Http\Requests\Api\User\StoreUserRequest;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

class UserStoreData extends Data
{
    public function __construct(
        public string $name,
        public string $email,

        #[WithTransformer(HashableTransformer::class)]
        public string $password
    ) {}

    public static function fromRequest(StoreUserRequest $request): self
    {
        return new self(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password')
        );
    }
}
