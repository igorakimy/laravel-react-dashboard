<?php

namespace App\Data\Invitation;

use App\Data\Role\RoleData;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class InvitationSendingData extends Data
{
    public function __construct(
        public string $email,

        #[DataCollectionOf(RoleData::class)]
        public DataCollection $roles,

        public ?string $message_text
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            roles: RoleData::collection(
                Role::query()->findMany($request->input('roles', []))
            ),
            message_text: $request->input('message_text')
        );
    }
}
