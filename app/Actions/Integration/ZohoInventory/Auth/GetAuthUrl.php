<?php

namespace App\Actions\Integration\ZohoInventory\Auth;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoBooks\Auth\AuthorizationUrlData;
use App\Services\ZohoInventory\ZohoInventoryService;

final class GetAuthUrl extends IntegrationAction
{
    public function __construct(
        private readonly ZohoInventoryService $zohoInventoryService,
    ) {
    }

    public function handle(): AuthorizationUrlData
    {
        $authURL = $this->zohoInventoryService->authURL();

        return AuthorizationUrlData::fromString($authURL);
    }
}
