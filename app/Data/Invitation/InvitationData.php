<?php

namespace App\Data\Invitation;

use App\Data\Role\RoleData;
use App\Data\User\UserData;
use App\Enums\InvitationState;
use App\Models\Invitation;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class InvitationData extends Data
{
    public function __construct(
        public int $id,
        public string $email,
        public ?string $url_token,
        public Lazy|UserData $sender,
        public Lazy|UserData $invitee,

        #[DataCollectionOf(RoleData::class)]
        public Lazy|DataCollection $roles,

        public ?string $message_text,

        public InvitationState $state,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon $expires_at,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $accepted_at,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $declined_at,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $revoked_at,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon $created_at,
    ) {
    }

    public static function fromModel(Invitation $invitation): self
    {
        return new self(
            id: $invitation->id,
            email: $invitation->email,
            url_token: $invitation->url_token,
            sender: Lazy::create(fn() => UserData::from($invitation->sender)),
            invitee: Lazy::create(fn() => $invitation->invitee ? UserData::from($invitation->invitee) : $invitation->email)->defaultIncluded(),
            roles: Lazy::create(fn() => RoleData::collection($invitation->allowedRoles)),
            message_text: $invitation->message_text,
            state: $invitation->state,
            expires_at: $invitation->expires_at,
            accepted_at: $invitation->accepted_at,
            declined_at: $invitation->declined_at,
            revoked_at: $invitation->revoked_at,
            created_at: $invitation->created_at,
        );
    }
}
