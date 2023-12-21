<?php

namespace App\Actions\Integration\ZohoCrm\Metadata;

use App\Actions\Api\ApiAction;
use App\Data\Integration\ZohoCrm\Metadata\Field\FieldsResponseData;
use App\Enums\ZohoCrmModule;
use App\Services\ZohoCrm\ZohoCrmService;

final class FetchAllMetadataFields extends ApiAction
{
    public function __construct(
       private readonly ZohoCrmService $zohoCrmService
    ) {
    }

    public function handle(): FieldsResponseData
    {
        return $this->zohoCrmService
            ->metadata()
            ->getFields(ZohoCrmModule::PRODUCTS);
    }
}
