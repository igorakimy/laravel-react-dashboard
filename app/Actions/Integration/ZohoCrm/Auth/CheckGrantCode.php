<?php

namespace App\Actions\Integration\ZohoCrm\Auth;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoCrm\Auth\AccessTokenData;
use App\Data\Integration\ZohoCrm\Auth\CheckGrantCodeData;
use App\Services\ZohoCrm\ZohoCrmService;
use Psr\SimpleCache\InvalidArgumentException;

final class CheckGrantCode extends IntegrationAction
{
    public function __construct(
        public ZohoCrmService $service
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
        return $this->service
            ->auth()
            ->getAccessTokenFromCode($data->code);
    }
}
