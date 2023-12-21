<?php

namespace App\Actions\Integration;

use App\Actions\Integration\ZohoCrm\Metadata\FetchAllMetadataFields;
use App\Data\Integration\IntegrationData;
use App\Data\Integration\ZohoCrm\Metadata\Field\FieldData as ZohoCrmFieldData;
use App\Data\IntegrationField\IntegrationFieldData;
use Exception;

final class StoreIntegrationFieldsFromZohoCrm extends IntegrationAction
{
    public function __construct(
        private readonly FetchAllMetadataFields $fetchAllMetadataFieldsAction,
        private readonly UpdateIntegrationField $updateIntegrationFieldAction,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(IntegrationData $integrationData): void
    {
        $data = $this->fetchAllMetadataFieldsAction->handle();

        if ( ! $data->success) {
            throw new Exception($data->message, 400);
        }

        /** @var ZohoCrmFieldData $fieldData */
        foreach ($data->fields as $fieldData) {
            $integrationFieldData = $this->getZohoCrmIntegrationFieldData(
                $integrationData,
                $fieldData
            );

            $this->updateIntegrationFieldAction->handle($integrationFieldData);
        }
    }

    /**
     * Get Zoho CRM integration field data.
     *
     * @param  IntegrationData  $integrationData
     * @param  ZohoCrmFieldData  $fieldData
     *
     * @return IntegrationFieldData
     */
    private function getZohoCrmIntegrationFieldData(
        IntegrationData $integrationData,
        ZohoCrmFieldData $fieldData
    ): IntegrationFieldData {
        return IntegrationFieldData::from([
            'integration'  => $integrationData,
            'field_id'     => $fieldData->id ?: null,
            'name'         => $fieldData->display_label,
            'api_name'     => $fieldData->api_name,
            'data_type'    => $fieldData->data_type,
            'order'        => $fieldData->quick_sequence_number ?: 0,
            'is_active'    => $fieldData->display_field,
            'is_custom'    => $fieldData->custom_field,
            'is_required'  => $fieldData->system_mandatory,
            'is_permanent' => true,
            'filterable'   => $fieldData->filterable,
            'searchable'   => $fieldData->searchable,
            'sortable'     => $fieldData->sortable,
            'values'       => $fieldData->pick_list_values,
        ]);
    }
}
