<?php

namespace App\Actions\Api\Setting;

use App\Data\Setting\ZohoCrmSettingsData;
use App\Settings\ZohoCrmSettings;

final class UpdateZohoCrmSettings
{
    public function __construct(
        public ZohoCrmSettings $settings
    ) {
    }

    public function handle(ZohoCrmSettingsData $data): ZohoCrmSettingsData
    {
        $this->settings->domain = $data->domain;
        $this->settings->client_id = $data->client_id;
        $this->settings->client_secret = $data->client_secret;
        $this->settings->redirect_uri = $data->redirect_uri;
        $this->settings->scopes = $data->scopes;

        $this->settings->save();

        return ZohoCrmSettingsData::from($this->settings->refresh());
    }
}
