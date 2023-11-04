<?php

namespace App\Listeners;

use App\Events\InvitationCreated;
use App\Mail\InvitationSent;
use Illuminate\Support\Facades\Mail;

class SendInvitationEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvitationCreated $event): void
    {
        Mail::to($event->inviteeEmail)
            ->queue(new InvitationSent(
                $event->senderFirstName,
                $event->senderLastName,
                $event->urlToken,
                $event->messageText
            ));
    }
}

