<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationSendingData;
use App\Data\Invitation\InvitationStoreData;
use App\Events\InvitationCreated;
use App\Exceptions\InvitationException;
use App\Models\User;
use Illuminate\Support\Facades\Event;

final class SendInvitation extends ApiAction
{
    public function __construct(
        public StoreInvitation $storeInvitationAction
    ) {
    }

    /**
     * @throws InvitationException
     */
    public function handle(InvitationSendingData $sendingData): ?InvitationData {

        // try to get user
        $user = User::query()
                    ->withTrashed()
                    ->where('email', $sendingData->email)
                    ->first();

        // check if user already exists
        if ($user) {
            throw InvitationException::userAlreadyExistsInSystem();
        }

        $storeData = new InvitationStoreData(
            email: $sendingData->email,
            message_text: $sendingData->message_text,
            roles: $sendingData->roles
        );

        // store invitation
        $invitationData = $this->storeInvitationAction->handle($storeData);

        // send invitation
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
