<?php

namespace App\Services\ZohoInventory;

use App\Services\Traits\BuildBaseRequest;
use App\Services\Traits\CanSendDeleteRequest;
use App\Services\Traits\CanSendGetRequest;
use App\Services\Traits\CanSendPostRequest;
use App\Services\Traits\CanSendPutRequest;
use App\Services\ZohoInventory\Resources\AuthResource;
use App\Services\ZohoInventory\Resources\CompositeItemResource;
use App\Services\ZohoInventory\Resources\ItemResource;
use App\Services\ZohoInventory\Resources\SettingResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

final class ZohoInventoryService
{
    use BuildBaseRequest;
    use CanSendGetRequest;
    use CanSendPostRequest;
    use CanSendPutRequest;
    use CanSendDeleteRequest;

    /**
     * @var string
     */
    public string $grantCode;

    /**
     * @var string
     */
    private string $accessToken;

    /**
     * Constructor.
     *
     * @param  string|null  $clientID
     * @param  string|null  $clientSecret
     * @param  string|null  $redirectUri
     * @param  string|null  $organizationID
     * @param  string  $domain
     * @param  array  $scopes
     * @param  int  $timeout
     * @param  string  $accessTokenCacheKey
     * @param  string  $refreshTokenCacheKey
     * @param  bool  $useCache
     * @param  string  $apiVersion
     */
    public function __construct(
        public ?string $clientID,
        public ?string $clientSecret,
        public ?string $redirectUri,
        public ?string $organizationID,
        public string $domain = 'com',
        public array $scopes = ['ZohoBooks.fullaccess.all'],
        public int $timeout = 10,
        public string $accessTokenCacheKey = 'zoho_inventory_access_token',
        public string $refreshTokenCacheKey = 'zoho_inventory_refresh_token',
        public bool $useCache = false,
        public string $apiVersion = 'v1',
    ) {
    }

    /**
     * Get api url.
     *
     * @return string
     */
    public function apiUrl(): string
    {
        return 'https://www.zohoapis.'.$this->domain.'/inventory/'.$this->apiVersion;
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
        $accessToken = $this->useCache
            ? $this->getTokenFromCache($this->accessTokenCacheKey)
            : $this->getAccessToken();

        return 'Zoho-oauthtoken '.$accessToken;
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
     * @return AuthResource
     */
    public function auth(): AuthResource
    {
        return new AuthResource($this);
    }

    /**
     * Get settings resource.
     *
     * @return SettingResource
     */
    public function settings(): SettingResource
    {
        return new SettingResource($this);
    }

    /**
     * Get items resource.
     *
     * @return ItemResource
     */
    public function items(): ItemResource
    {
        return new ItemResource($this);
    }

    /**
     * Get composite items resource.
     *
     * @return CompositeItemResource
     */
    public function compositeItems(): CompositeItemResource
    {
        return new CompositeItemResource($this);
    }

    /**
     * Get api version.
     *
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Get access token.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken ?? '';
    }

    /**
     * Set new access token.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function setAccessToken(string $token): void
    {
        $this->accessToken = $token;
    }

    /**
     * Get token from cache.
     *
     * @param  string  $tokenKey
     *
     * @return string|null
     */
    public function getTokenFromCache(string $tokenKey): ?string
    {
        return Cache::get($tokenKey);
    }

    /**
     * Store token to cache.
     *
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
