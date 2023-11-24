<?php

namespace App\Services\ZohoBooks\Resources;

use App\Data\Integration\ZohoBooks\Auth\AccessTokenData;
use App\Services\ZohoBooks\ZohoBooksService;
use Psr\SimpleCache\InvalidArgumentException;

final class OauthResource
{
    private int $accessTokenLifetime = 30;

    public function __construct(
        private readonly ZohoBooksService $service
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
                    time: now()->addYear()
                );
            }
        }

        return $data;
    }
}
