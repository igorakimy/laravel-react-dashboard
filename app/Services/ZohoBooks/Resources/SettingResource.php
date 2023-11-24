<?php

namespace App\Services\ZohoBooks\Resources;

use App\Data\Integration\ZohoBooks\Setting\FieldsData;
use App\Services\ZohoBooks\ZohoBooksService;

final class SettingResource
{
    public function __construct(
        private readonly ZohoBooksService $service
    ) {
    }

    /**
     * Get entity fields.
     *
     * @param  string  $entity
     *
     * @return FieldsData
     */
    public function getFields(string $entity): FieldsData
    {
        $url = '/settings/fields';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'entity' => $entity,
            'organization_id' => $this->service->organizationID,
        ]);

        return FieldsData::fromResponse($response);
    }
}
