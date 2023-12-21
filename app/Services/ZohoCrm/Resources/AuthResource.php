<?php

namespace App\Services\ZohoCrm\Resources;

use App\Data\Integration\ZohoCrm\Auth\AccessTokenData;
use App\Services\ZohoCrm\ZohoCrmService;
use Psr\SimpleCache\InvalidArgumentException;

final class AuthResource
{
    /**
     * Access token lifetime in minutes.
     *
     * @var int
     */
    private int $accessTokenLifetime = 60;

    public function __construct(
        private readonly ZohoCrmService $service
    ) {
    }

    /**
     * Get access token.
     *
     * @param  string|null  $code
     *
     * @return AccessTokenData
     * @throws InvalidArgumentException
     */
    public function getAccessTokenFromCode(string|null $code): AccessTokenData
    {
        $url = '/oauth/v2/token';

        $this->service->setGrantCode($code ?? 'empty');

        $data = [
            'code'          => $this->service->grantCode,
            'client_id'     => $this->service->clientID,
            'client_secret' => $this->service->clientSecret,
            'redirect_uri'  => $this->service->redirectUri,
            'grant_type'    => 'authorization_code',
        ];

        $request = $this->service->buildRequest(
            customUrl: $this->service->accountsUrl()
        );

        $response = $this->service->post($request, $url, $data, true);

        $data = AccessTokenData::fromResponse($response);

        if ( ! $this->service->useCache) {
            return $data;
        }

        if ($data->success) {

            if ($data->access_token) {
                $this->service->setTokenToCache(
                    tokenKey: $this->service->accessTokenCacheKey,
                    token: $data->access_token,
                    time: now()->addMinutes($this->accessTokenLifetime)
                );
            }

            if ($data->refresh_token) {
                $this->service->setTokenToCache(
                    tokenKey: $this->service->refreshTokenCacheKey,
                    token: $data->refresh_token,
                    time: now()->addYears(10)
                );
            }
        }

        return $data;
    }
}
