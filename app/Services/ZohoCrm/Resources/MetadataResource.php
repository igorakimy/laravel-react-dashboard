<?php

namespace App\Services\ZohoCrm\Resources;

use App\Data\Integration\ZohoCrm\Metadata\Field\FieldsResponseData;
use App\Enums\ZohoCrmModule;
use App\Services\ZohoCrm\ZohoCrmService;

final class MetadataResource
{
    public function __construct(
        private readonly ZohoCrmService $service,
    ) {
    }

    /**
     * Get module fields.
     *
     * @param  ZohoCrmModule  $module
     * @param  string  $type "all" or "unused"
     *
     * @return FieldsResponseData
     */
    public function getFields(ZohoCrmModule $module, string $type = 'all'): FieldsResponseData
    {
        $url = '/settings/fields';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'module' => $module->value,
            'type' => $type,
        ]);

        return FieldsResponseData::fromResponse($response);
    }
}
