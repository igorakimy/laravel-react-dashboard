<?php

namespace App\Actions\Integration;

use App\Data\IntegrationField\IntegrationFieldData;
use App\Models\IntegrationField;

final class UpdateIntegrationField extends IntegrationAction
{
    public function handle(IntegrationFieldData $data): IntegrationFieldData
    {
        $integrationField = IntegrationField::query()->updateOrCreate([
            'api_name' => $data->api_name,
            'integration_id' => $data->integration->id,
        ], [
            'field_id' => $data->field_id,
            'name' => $data->name,
            'data_type' => $data->data_type,
            'order' => $data->order,
            'is_active' => $data->is_active,
            'is_custom' => $data->is_custom,
            'is_required' => $data->is_required,
            'is_permanent' => $data->is_permanent,
            'filterable' => $data->filterable,
            'searchable' => $data->searchable,
            'sortable' => $data->sortable,
            'values' => $data->values,
        ]);

        return IntegrationFieldData::from($integrationField);
    }
}
