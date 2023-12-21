<?php

namespace App\Actions\Integration;

use App\Actions\Integration\ZohoInventory\Setting\FetchAllItemFields;
use App\Data\Integration\IntegrationData;
use App\Data\Integration\ZohoInventory\Setting\FieldData as ZohoInventoryFieldData;
use App\Data\IntegrationField\IntegrationFieldData;
use Exception;

final class StoreIntegrationFieldsFromZohoInventory extends IntegrationAction
{
    public function __construct(
        private readonly FetchAllItemFields $fetchAllItemFieldsAction,
        private readonly UpdateIntegrationField $updateIntegrationFieldAction,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(IntegrationData $integrationData): void
    {
        $data = $this->fetchAllItemFieldsAction->handle();

        if ( ! $data->success) {
            throw new Exception($data->message, 400);
        }

        /** @var ZohoInventoryFieldData $fieldData */
        foreach ($data->fields as $fieldData) {
            $integrationFieldData = $this->getZohoInventoryIntegrationFieldData(
                $integrationData,
                $fieldData
            );

            $this->updateIntegrationFieldAction->handle($integrationFieldData);
        }
    }

    /**
     * Get Zoho Inventory integration field data.
     *
     * @param  IntegrationData  $integrationData
     * @param  ZohoInventoryFieldData  $fieldData
     *
     * @return IntegrationFieldData
     */
    private function getZohoInventoryIntegrationFieldData(
        IntegrationData $integrationData,
        ZohoInventoryFieldData $fieldData
    ): IntegrationFieldData {
        return IntegrationFieldData::from([
            'integration'  => $integrationData,
            'field_id'     => $fieldData->field_id ?: null,
            'name'         => $fieldData->field_name_formatted,
            'api_name'     => $fieldData->field_name,
            'data_type'    => $fieldData->data_type,
            'order'        => $fieldData->index ?: 0,
            'is_active'    => $fieldData->is_active,
            'is_custom'    => $fieldData->is_custom_field,
            'is_required'  => $fieldData->is_mandatory,
            'is_permanent' => true,
            'filterable'   => true,
            'searchable'   => true,
            'sortable'     => true,
            'values'       => $fieldData->values,
        ]);
    }
}
