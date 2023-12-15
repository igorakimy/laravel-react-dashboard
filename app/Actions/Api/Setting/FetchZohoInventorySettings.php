<?php

namespace App\Actions\Api\Setting;

use App\Data\Setting\ZohoInventorySettingsData;
use App\Settings\ZohoInventorySettings;

final class FetchZohoInventorySettings
{
    public function __construct(
        public ZohoInventorySettings $zohoInventorySettings
    ) {
    }

    public function handle(): ZohoInventorySettingsData
    {
        return ZohoInventorySettingsData::from($this->zohoInventorySettings);
    }
}
