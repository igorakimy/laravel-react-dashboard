<?php

namespace App\Actions\Api\Invitation;

use App\Actions\Api\ApiAction;
use App\Data\Invitation\InvitationData;
use App\Data\Invitation\InvitationPaginationData;
use App\Data\Invitation\InvitationSortingData;
use App\Data\SortingData;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedInvitations extends ApiAction
{
    public function handle(
        InvitationPaginationData $pagination,
        InvitationSortingData $sorting
    ): PaginatedDataCollection {

        $query = Invitation::query()->with($this->getRelations());

        $query = $this->applySorting($query, $sorting);

        $invitations = $query->paginate(
            perPage: $pagination->pageSize,
            columns: $pagination->columns,
            pageName: $pagination->pageName,
            page: $pagination->currentPage,
        );

        return InvitationData::collection($invitations)->include(
            'roles',
            'sender',
            'invitee'
        );
    }

    /**
     * Apply sorting.
     *
     * @param  Builder|Invitation  $query
     * @param  SortingData  $sorting
     *
     * @return Builder|Invitation
     */
    private function applySorting(Builder|Invitation $query, SortingData $sorting): Builder|Invitation
    {
        if ($sorting->column === 'sender') {
            $query = $query->orderBySender($sorting->direction);
        } elseif ($sorting->column === 'invitee') {
            $query = $query->orderByInvitee($sorting->direction);
        } elseif ($sorting->column === 'status') {
            $query = $query->orderByStatus($sorting->direction);
        } else {
            $query = $query->orderBy($sorting->column, $sorting->direction);
        }

        return $query;
    }

    /**
     * Get invitation relations.
     *
     * @return string[]
     */
    private function getRelations(): array
    {
        return [
            'sender',
            'invitee',
            'allowedRoles'
        ];
    }
}
