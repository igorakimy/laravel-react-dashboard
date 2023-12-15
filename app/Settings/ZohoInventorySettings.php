<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ZohoInventorySettings extends Settings
{
    public string|null $client_id;

    public string|null $client_secret;

    public string|null $redirect_uri;

    public string|null $organization_id;

    public string|null $domain;

    public array $scopes;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'zoho_inventory';
    }
}
