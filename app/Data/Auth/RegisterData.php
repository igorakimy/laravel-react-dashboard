<?php

namespace App\Data\Auth;

use App\Data\Transformers\HashableTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

final class RegisterData extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,

        #[WithTransformer(HashableTransformer::class)]
        public string $password,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }

    public static function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->numbers(),
                'confirmed'
            ]
        ];
    }
}
