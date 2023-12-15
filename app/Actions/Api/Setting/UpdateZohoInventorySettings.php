<?php

namespace App\Actions\Api\Setting;

use App\Data\Setting\ZohoInventorySettingsData;
use App\Settings\ZohoInventorySettings;

final class UpdateZohoInventorySettings
{
    public function __construct(
        public ZohoInventorySettings $settings
    ) {
    }

    public function handle(ZohoInventorySettingsData $data): ZohoInventorySettingsData
    {
        $this->settings->domain = $data->domain;
        $this->settings->client_id = $data->client_id;
        $this->settings->client_secret = $data->client_secret;
        $this->settings->redirect_uri = $data->redirect_uri;
        $this->settings->organization_id = $data->organization_id;
        $this->settings->scopes = $data->scopes;

        $this->settings->save();

        return ZohoInventorySettingsData::from($this->settings->refresh());
    }
}
