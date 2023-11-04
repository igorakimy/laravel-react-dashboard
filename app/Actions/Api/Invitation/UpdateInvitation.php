<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationUpdateData;
use App\Models\Invitation;

final class UpdateInvitation extends ApiAction
{
    public function handle(Invitation $invitation, InvitationUpdateData $data): InvitationData
    {
        $invitation->update([
            'expires_at' => $data->expires_at,
            'url_token' => $data->url_token,
        ]);

        return InvitationData::from($invitation);
    }
}
