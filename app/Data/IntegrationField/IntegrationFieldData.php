<?php

namespace App\Data\IntegrationField;

use App\Data\Integration\IntegrationData;
use App\Models\IntegrationField;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;

final class IntegrationFieldData extends Data
{
    public function __construct(
        public Optional|int $id,

        public Lazy|IntegrationData $integration,

        public ?string $field_id,
        public string $name,
        public string $api_name,
        public string $data_type,

        public ?string $order,

        public ?bool $is_active,
        public ?bool $is_custom,
        public ?bool $is_required,
        public ?bool $is_primary,
        public ?bool $is_permanent,

        public ?bool $filterable,
        public ?bool $searchable,
        public ?bool $sortable,

        public Optional|array $values,
    ) {
    }

    public static function fromModel(IntegrationField $integrationField): self
    {
        return new self(
            id: $integrationField->id,
            integration: Lazy::create(fn() => IntegrationData::from($integrationField->integration)),
            field_id: $integrationField->field_id,
            name: $integrationField->name,
            api_name: $integrationField->api_name,
            data_type: $integrationField->data_type,
            order: $integrationField->order,
            is_active: $integrationField->is_active,
            is_custom: $integrationField->is_custom,
            is_required: $integrationField->is_required,
            is_primary: $integrationField->is_primary,
            is_permanent: $integrationField->is_permanent,
            filterable: $integrationField->filterable,
            searchable: $integrationField->searchable,
            sortable: $integrationField->sortable,
            values: $integrationField->values,
        );
    }
}
