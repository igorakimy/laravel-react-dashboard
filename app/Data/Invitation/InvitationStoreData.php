<?php

namespace App\Data\Invitation;

use App\Data\Role\RoleData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class InvitationStoreData extends Data
{
    public function __construct(
        public string $email,
        public ?string $message_text,

        #[DataCollectionOf(RoleData::class)]
        public DataCollection $roles,
    ) {
    }
}
