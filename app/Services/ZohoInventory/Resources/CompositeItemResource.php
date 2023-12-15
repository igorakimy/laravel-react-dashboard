<?php

namespace App\Services\ZohoInventory\Resources;

use App\Data\Integration\ZohoInventory\CompositeItem\CompositeItemsResponseData;
use App\Services\ZohoInventory\ZohoInventoryService;

final class CompositeItemResource
{
    public function __construct(
        private readonly ZohoInventoryService $zohoInventoryService,
    ) {
    }

    /**
     * Get the list of all composite items.
     *
     * @return CompositeItemsResponseData
     */
    public function getList(): CompositeItemsResponseData
    {
        $url = '/compositeitems';

        $request = $this->zohoInventoryService->buildRequestWithToken();

        $response = $this->zohoInventoryService->get($request, $url, [
            'organization_id' => $this->zohoInventoryService->organizationID,
        ]);

        return CompositeItemsResponseData::fromResponse($response);
    }
}
