<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationStoreData;
use App\Models\Invitation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class StoreInvitation extends ApiAction
{
    public function handle(InvitationStoreData $data): InvitationData
    {
        /** @var Invitation $invitation */
        $invitation = Invitation::query()->create([
            'email' => $data->email,
            'url_token' => md5(Str::uuid()),
            'sender_id' => auth()->id(),
            'message_text' => $data->message_text,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $rolesIDs = $data->roles->toCollection()->pluck('id')->toArray();

        $invitation->allowedRoles()->sync($rolesIDs);

        return InvitationData::from($invitation);
    }
}
