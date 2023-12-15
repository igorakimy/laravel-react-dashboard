<?php

namespace App\Services\ZohoInventory\Resources;


use App\Data\Integration\ZohoInventory\Setting\FieldsResponseData;
use App\Services\ZohoInventory\ZohoInventoryService;

final class SettingResource
{
    public function __construct(
        private readonly ZohoInventoryService $service
    ) {
    }

    /**
     * Get entity fields.
     *
     * @param  string  $entity
     *
     * @return FieldsResponseData
     */
    public function getFields(string $entity): FieldsResponseData
    {
        $url = '/settings/fields';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'entity' => $entity,
            'organization_id' => $this->service->organizationID,
        ]);

        return FieldsResponseData::fromResponse($response);
    }
}
