<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\User\UserData;
use App\Data\User\UserPaginationData;
use App\Data\User\UserSortingData;
use App\Models\Role;
use App\Models\User;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedUsers extends ApiAction
{
    public function handle(UserPaginationData $pagination, UserSortingData $sorting): PaginatedDataCollection
    {
        $query = User::query()->with(['roles', 'roles.permissions']);

        // apply sorting.
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

        // paginate.
        $users = $query->paginate(
            $pagination->pageSize,
            $pagination->columns,
            $pagination->pageName,
            $pagination->currentPage
        );

        return UserData::collection($users);
    }
}
