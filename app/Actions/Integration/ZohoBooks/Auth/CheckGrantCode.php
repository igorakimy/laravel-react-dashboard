<?php

namespace App\Actions\Integration\ZohoBooks\Auth;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoBooks\Auth\AccessTokenData;
use App\Data\Integration\ZohoBooks\Auth\CheckGrantCodeData;
use App\Services\ZohoBooks\ZohoBooksService;
use Psr\SimpleCache\InvalidArgumentException;

final class CheckGrantCode extends IntegrationAction
{
    public function __construct(
        public ZohoBooksService $zohoBooksService
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

        return $this->zohoBooksService
            ->auth()
            ->getAccessTokenFromCode($grantCode);
    }
}
