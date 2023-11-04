<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Models\Invitation;
use Carbon\Carbon;

final class RevokeInvitation extends ApiAction
{
    public function handle(Invitation $invitation): bool
    {
        return $invitation->update([
            'revoked_at' => Carbon::now()
        ]);
    }
}
