<?php

namespace App\Actions\Api\Setting;

use App\Data\Setting\ZohoCrmSettingsData;
use App\Settings\ZohoCrmSettings;

final class FetchZohoCrmSettings
{
    public function __construct(
        public ZohoCrmSettings $zohoCrmSettings
    ) {
    }

    public function handle(): ZohoCrmSettingsData
    {
        return ZohoCrmSettingsData::from($this->zohoCrmSettings);
    }
}
