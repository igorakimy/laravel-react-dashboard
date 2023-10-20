<?php

namespace App\Actions\Api\Setting;

use App\Data\Setting\ZohoBooksSettingsData;
use App\Settings\ZohoBooksSettings;

final class FetchZohoBooksSettings
{
    public function __construct(
        public ZohoBooksSettings $zohoBooksSettings
    ) {
    }

    public function handle(): ZohoBooksSettingsData
    {
        return ZohoBooksSettingsData::from($this->zohoBooksSettings);
    }
}
