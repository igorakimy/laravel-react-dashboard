<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Invitation\FetchPaginatedInvitations;
use App\Actions\Api\Invitation\ResendInvitation;
use App\Actions\Api\Invitation\RevokeInvitation;
use App\Actions\Api\Invitation\SendInvitation;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationPaginationData;
use App\Data\Invitation\InvitationSendingData;
use App\Exceptions\InvitationException;
use App\Http\Controllers\ApiController;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class InvitationController extends ApiController
{
    public function __construct(
        private readonly FetchPaginatedInvitations $fetchPaginatedInvitationsAction,
        private readonly SendInvitation $sendInvitationAction,
        private readonly ResendInvitation $resendInvitationAction,
        private readonly RevokeInvitation $revokeInvitationAction,
    ) {
    }

    /**
     * Get all invitations.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pagination = InvitationPaginationData::fromRequest($request);

        $invitations = $this->fetchPaginatedInvitationsAction->handle($pagination);

        return response($invitations);
    }

    /**
     * Send new invitation.
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws InvitationException
     */
    public function send(Request $request): Response
    {
        $data = InvitationSendingData::fromRequest($request);

        $this->sendInvitationAction->handle($data);

        return response([
            'status' => 'success',
            'message' => 'Invitation successfully sent.'
        ]);
    }

    /**
     * Resend invitation.
     *
     * @param  Invitation  $invitation
     *
     * @return Response
     * @throws InvitationException
     */
    public function resend(Invitation $invitation): Response
    {
        if ($invitation->isAccepted()) {
            throw InvitationException::invitationAlreadyAccepted();
        }

        if ($invitation->isExpired()) {
            throw InvitationException::invitationAlreadyExpired();
        }

        if ($invitation->isRevoked()) {
            $invitation->update(['revoked_at' => null]);
        }

        if ($invitation->isPending()) {
            $invitationData = InvitationData::from($invitation);
            $this->resendInvitationAction->handle(
                $invitation,
                $invitationData
            );
        }

        return response([
            'status' => 'success',
            'message' => "Invitation can't be resend."
        ]);
    }

    /**
     * Revoke invitation.
     *
     * @param  Invitation  $invitation
     *
     * @return Response
     * @throws InvitationException
     * @
     */
    public function revoke(Invitation $invitation): Response
    {
        if ($invitation->isPending()) {
            $this->revokeInvitationAction->handle($invitation);
            return response([
                'status' => 'success',
                'message' => 'invitation successfully revoked'
            ]);
        }

        if ($invitation->isAccepted()) {
            throw InvitationException::invitationAlreadyAccepted();
        }

        if ($invitation->isRevoked()) {
            throw InvitationException::invitationAlreadyRevoked();
        }

        if ($invitation->isExpired()) {
            throw InvitationException::invitationAlreadyExpired();
        }

        throw InvitationException::invitationCanNotBeRevoked();
    }
}
