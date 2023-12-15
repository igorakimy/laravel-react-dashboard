<?php

namespace App\Actions\Integration\ZohoInventory\Setting;

use App\Actions\Integration\IntegrationAction;
use App\Data\Integration\ZohoInventory\Setting\FieldsResponseData;
use App\Services\ZohoInventory\ZohoInventoryService;

final class FetchAllItemFields extends IntegrationAction
{
    public function __construct(
        private readonly ZohoInventoryService $zohoInventoryService,
    ) {
    }

    public function handle(): FieldsResponseData
    {
        return $this->zohoInventoryService
            ->settings()
            ->getFields('item');
    }
}
