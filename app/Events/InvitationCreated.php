<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        public string $senderFirstName,
        public string $senderLastName,
        public string $inviteeEmail,
        public string $urlToken,
        public ?string $messageText
    ) {
    }
}
