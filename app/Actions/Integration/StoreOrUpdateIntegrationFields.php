<?php

namespace App\Actions\Integration;

use App\Data\Integration\IntegrationData;
use App\Enums\Integration as IntegrationSystem;
use Exception;

final class StoreOrUpdateIntegrationFields extends IntegrationAction
{
    public function __construct(
        private readonly StoreIntegrationFieldsFromZohoBooks $storeIntegrationFieldsFromZohoBooksAction,
        private readonly StoreIntegrationFieldsFromZohoInventory $storeIntegrationFieldsFromZohoInventoryAction,
        private readonly StoreIntegrationFieldsFromZohoCrm $storeIntegrationFieldsFromZohoCrmAction,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(IntegrationData $integrationData): void
    {
        $integrationSystem = IntegrationSystem::from($integrationData->slug);

        switch ($integrationSystem) {
            case IntegrationSystem::ZOHO_BOOKS:
                $this->storeIntegrationFieldsFromZohoBooksAction->handle($integrationData);
                break;
            case IntegrationSystem::ZOHO_INVENTORY:
                $this->storeIntegrationFieldsFromZohoInventoryAction->handle($integrationData);
                break;
            case IntegrationSystem::ZOHO_CRM:
                $this->storeIntegrationFieldsFromZohoCrmAction->handle($integrationData);
                break;
        }
    }
}
