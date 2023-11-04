<?php

namespace App\Data\Invitation;

use Spatie\LaravelData\Data;

final class InvitationResponseData extends Data
{
    public function __construct(
        public bool $success,
        public ?string $message
    ) {
    }
}
