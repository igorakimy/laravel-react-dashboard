<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\SortingData;
use App\Data\User\UserData;
use App\Data\User\UserPaginationData;
use App\Data\User\UserSortingData;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedUsers extends ApiAction
{
    public function handle(UserPaginationData $pagination, UserSortingData $sorting): PaginatedDataCollection
    {
        $query = User::query()->with($this->getRelations());

        $query = $this->applySorting($query, $sorting);

        $users = $query->paginate(
            $pagination->pageSize,
            $pagination->columns,
            $pagination->pageName,
            $pagination->currentPage
        );

        return UserData::collection($users)->include(
            'roles',
            'phones'
        );
    }

    /**
     * Apply sorting.
     *
     * @param  Builder  $query
     * @param  SortingData  $sorting
     *
     * @return Builder
     */
    private function applySorting(Builder $query, SortingData $sorting): Builder
    {
        if ($sorting->column == 'roles') {
            $query->orderBy(
                Role::query()->selectRaw('STRING_AGG(name, \', \')')
                    ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->whereColumn('model_has_roles.model_id', '=', 'users.id')
                    ->where('model_has_roles.model_type', '=', User::class)
                    ->groupBy('model_has_roles.model_id')
            );
        } elseif ($sorting->column == 'name') {
            $query->orderByRaw("CONCAT(first_name, ' ', last_name) $sorting->direction");
        } else {
            $query->orderBy($sorting->column, $sorting->direction);
        }

        return $query;
    }

    /**
     * Get user relations list.
     *
     * @return string[]
     */
    private function getRelations(): array
    {
        return [
            'roles',
            'roles.permissions',
            'phones'
        ];
    }
}
