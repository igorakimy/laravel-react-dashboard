<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationUpdateData;
use App\Events\InvitationCreated;
use App\Models\Invitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

final class ResendInvitation extends ApiAction
{
    public function __construct(
        public UpdateInvitation $updateInvitationAction,
    ) {
    }

    public function handle(Invitation $invitation, InvitationData $invitationData): ?InvitationData {

        $updateData = new InvitationUpdateData(
            expires_at: Carbon::now()->addMinutes(5),
            created_at: Carbon::now(),
            url_token: Invitation::generateToken(),
        );

        $this->updateInvitationAction->handle($invitation, $updateData);

        Event::dispatch(new InvitationCreated(
            senderFirstName: $invitationData->sender->first_name,
            senderLastName: $invitationData->sender->last_name,
            inviteeEmail: $invitationData->email,
            urlToken: $invitationData->url_token,
            messageText: $invitationData->message_text
        ));

        return $invitationData;
    }
}
