<?php

namespace App\Services\ZohoBooks;

use App\Services\Traits\BuildBaseRequest;
use App\Services\Traits\CanSendGetRequest;
use App\Services\Traits\CanSendPostRequest;
use App\Services\ZohoBooks\Resources\OauthResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

final class ZohoBooksService
{
    use BuildBaseRequest;
    use CanSendGetRequest;
    use CanSendPostRequest;

    /**
     * @var string
     */
    public string $grantCode;

    public function __construct(
        public ?string $clientID,
        public ?string $clientSecret,
        public ?string $redirectUri,
        public ?string $organizationID,
        public string $domain = 'com',
        public array $scopes = ['ZohoBooks.fullaccess.all'],
        public int $timeout = 10,
        public string $accessTokenCacheKey = 'zoho_books_access_token',
        public string $refreshTokenCacheKey = 'zoho_books_refresh_token',
    ) {
    }

    /**
     * Get api url.
     *
     * @return string
     */
    public function apiUrl(): string
    {
        return 'https://books.zoho.'.$this->domain;
    }

    /**
     * Get accounts url.
     *
     * @return string
     */
    public function accountsUrl(): string
    {
        return 'https://accounts.zoho.'.$this->domain;
    }

    /**
     * Set new grant code.
     *
     * @param  string  $code
     *
     * @return void
     */
    public function setGrantCode(string $code): void
    {
        $this->grantCode = $code;
    }

    /**
     * Get token string to access to api.
     *
     * @return string
     */
    public function apiToken(): string
    {
        return 'Zoho-oauthtoken '.$this->getTokenFromCache($this->accessTokenCacheKey);
    }

    /**
     * Get the authorization URL.
     *
     * @return string
     */
    public function authURL(): string
    {
        $url = $this->accountsUrl().'/oauth/v2/auth?';

        $queryData = [
            'scope'         => implode(',', $this->scopes),
            'client_id'     => $this->clientID,
            'response_type' => 'code',
            'redirect_uri'  => $this->redirectUri,
            'access_type'   => 'offline',
            'prompt'        => 'consent'
        ];

        foreach ($queryData as $key => $value) {
            $url .= "$key=$value&";
        }

        return rtrim($url, '&');
    }

    /**
     * Get auth resource.
     *
     * @return OauthResource
     */
    public function auth(): OauthResource
    {
        return new OauthResource($this);
    }

    /**
     * Get token from cache.
     *
     * @param  string  $tokenKey
     *
     * @return string
     */
    public function getTokenFromCache(string $tokenKey): string
    {
        return Cache::get($tokenKey);
    }

    /**
     * @param  string  $tokenKey
     * @param  string  $token
     * @param  Carbon  $time
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setTokenToCache(string $tokenKey, string $token, Carbon $time): void
    {
        Cache::set($tokenKey, $token, $time);
    }
}
