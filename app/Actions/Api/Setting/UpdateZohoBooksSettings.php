<?php

namespace App\Actions\Api\Setting;

use App\Data\Setting\ZohoBooksSettingsData;
use App\Settings\ZohoBooksSettings;

final class UpdateZohoBooksSettings
{
    public function __construct(
        public ZohoBooksSettings $settings
    ) {
    }

    public function handle(ZohoBooksSettingsData $data): ZohoBooksSettingsData
    {
        $this->settings->domain = $data->domain;
        $this->settings->client_id = $data->client_id;
        $this->settings->client_secret = $data->client_secret;
        $this->settings->redirect_uri = $data->redirect_uri;
        $this->settings->organization_id = $data->organization_id;
        $this->settings->scopes = $data->scopes;

        $this->settings->save();

        return ZohoBooksSettingsData::from($this->settings->refresh());
    }
}
