<?php

namespace App\Actions\Api\Invitation;

use App\Exceptions\InvitationException;
use App\Models\Invitation;
use Spatie\LaravelData\Data;

final class ShowInvitation extends Data
{
    /**
     * @throws InvitationException
     */
    public function handle(string $token): Invitation
    {
        $invitation = Invitation::query()
                                ->with(['sender', 'invitee', 'allowedRoles'])
                                ->where('url_token', $token)
                                ->first();

        if ( ! $invitation) {
            throw InvitationException::invitationNotFound();
        }

        return $invitation;
    }
}
