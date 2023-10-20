<?php

namespace App\Actions\Integration\ZohoBooks\Auth;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoBooks\Auth\AuthorizationUrlData;
use App\Services\ZohoBooks\ZohoBooksService;

final class GetAuthUrl extends IntegrationAction
{
    public function __construct(
        private readonly ZohoBooksService $zohoBooksService,
    ) {
    }

    public function handle(): AuthorizationUrlData
    {
        $authURL = $this->zohoBooksService->authURL();

        return AuthorizationUrlData::fromString($authURL);
    }
}
