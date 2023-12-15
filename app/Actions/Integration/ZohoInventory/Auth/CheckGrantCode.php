<?php

namespace App\Actions\Integration\ZohoInventory\Auth;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoInventory\Auth\AccessTokenData;
use App\Data\Integration\ZohoInventory\Auth\CheckGrantCodeData;
use App\Services\ZohoInventory\ZohoInventoryService;
use Psr\SimpleCache\InvalidArgumentException;

final class CheckGrantCode extends IntegrationAction
{
    public function __construct(
        public ZohoInventoryService $zohoInventoryService
    ) {
    }

    /**
     * @param  CheckGrantCodeData  $data
     *
     * @return AccessTokenData
     * @throws InvalidArgumentException
     */
    public function handle(CheckGrantCodeData $data): AccessTokenData
    {
        $grantCode = $data->code;

        return $this->zohoInventoryService
            ->auth()
            ->getAccessTokenFromCode($grantCode);
    }
}
