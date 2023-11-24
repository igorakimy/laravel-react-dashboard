<?php

namespace App\Data\Integration;

use App\Data\IntegrationField\IntegrationFieldData;
use App\Data\LocalField\LocalFieldData;
use App\Models\IntegrationField;
use Spatie\LaravelData\Data;

final class FieldsMappedData extends Data
{
    public function __construct(
        public IntegrationFieldData $integration_field,
        public ?LocalFieldData $local_field,
    ) {
    }

    public static function fromModel(IntegrationField $integrationField): self
    {
        return new self(
            integration_field: IntegrationFieldData::from($integrationField),
            local_field: !$integrationField->is_primary
                ? LocalFieldData::from($integrationField->localFields()->first())
                : null,
        );
    }
}
