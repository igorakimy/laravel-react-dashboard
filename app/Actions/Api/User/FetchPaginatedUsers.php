<?php

namespace App\Actions\Api\User;

use App\Actions\Api\ApiAction;
use App\Data\User\UserData;
use App\Data\User\UserPaginationData;
use App\Models\Role;
use App\Models\User;
use Spatie\LaravelData\PaginatedDataCollection;

final class FetchPaginatedUsers extends ApiAction
{
    public function handle(UserPaginationData $data): PaginatedDataCollection
    {
        $query = User::query()->with(['roles', 'roles.permissions']);

        if ($data->sortColumn == 'roles') {
            $query->orderBy(Role::query()->selectRaw('STRING_AGG(name, \', \')')
                                ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
                                ->whereColumn('model_has_roles.model_id', '=', 'users.id')
                                ->where('model_has_roles.model_type', '=', User::class)
                                ->groupBy('model_has_roles.model_id'));
        } elseif ($data->sortColumn == 'name') {
            $query->orderByRaw("concat(first_name, ' ', last_name) $data->sortDirection");
        } else {
            $query->orderBy($data->sortColumn, $data->sortDirection);
        }

        $users = $query->paginate(
                           $data->pageSize,
                           ['*'],
                           'page',
                           $data->currentPage
                       );

        return UserData::collection($users);
    }
}
