<?php

namespace App\Actions\Integration\ZohoBooks\Setting;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoBooks\Setting\FieldsData;
use App\Services\ZohoBooks\ZohoBooksService;

final class FetchAllItemFields extends IntegrationAction
{
    public function __construct(
        private readonly ZohoBooksService $zohoBooksService,
    ) {
    }

    public function handle(): FieldsData
    {
        return $this->zohoBooksService
            ->settings()
            ->getFields('item');
    }
}
