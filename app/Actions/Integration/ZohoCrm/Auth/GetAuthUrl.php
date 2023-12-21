<?php

namespace App\Actions\Integration\ZohoCrm\Auth;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoBooks\Auth\AuthorizationUrlData;
use App\Services\ZohoCrm\ZohoCrmService;

final class GetAuthUrl extends IntegrationAction
{
    public function __construct(
        private readonly ZohoCrmService $service,
    ) {
    }

    public function handle(): AuthorizationUrlData
    {
        $authURL = $this->service->authURL();

        return AuthorizationUrlData::fromString($authURL);
    }
}
