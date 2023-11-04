<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationPaginationData;
use App\Models\Invitation;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedInvitations extends ApiAction
{
    public function handle(InvitationPaginationData $pagination): PaginatedDataCollection
    {
        $invitations = Invitation::query()->with([
            'sender',
            'invitee',
            'allowedRoles'
        ])->paginate(
            perPage: $pagination->pageSize,
            columns: $pagination->columns,
            pageName: $pagination->pageName,
            page: $pagination->currentPage,
        );

        return InvitationData::collection($invitations)
                             ->include('sender', 'invitee', 'roles');
    }
}
