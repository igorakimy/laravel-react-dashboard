<?php

namespace App\Data\Integration\ZohoBooks\Item;

use App\Data\Integration\ZohoBooks\Account\AccountData;
use App\Data\Integration\ZohoBooks\CustomField\CustomFieldData;
use App\Data\Integration\ZohoBooks\Tax\TaxData;
use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

final class ItemEditPageResponseData extends Data
{
    public function __construct(
        public bool $success,

        public ItemData $item,

        #[DataCollectionOf(CustomFieldData::class)]
        public DataCollection $entity_fields,

        #[DataCollectionOf(AccountData::class)]
        public DataCollection|Optional $income_accounts_list,

        #[DataCollectionOf(AccountData::class)]
        public DataCollection|Optional $purchase_accounts_list,

        #[DataCollectionOf(AccountData::class)]
        public DataCollection|Optional $inventory_accounts_list,

        #[DataCollectionOf(TaxData::class)]
        public DataCollection|Optional $taxes,

        public string $message
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $item = $response->json('item');
        $entityFields = $response->json('entity_fields');
        $incomeAccounts = $response->json('income_accounts_list');
        $purchaseAccounts = $response->json('purchase_accounts_list');
        $inventoryAccounts = $response->json('inventory_accounts_list');
        $taxes = $response->json('taxes');

        return new self(
            success: $response->successful(),
            item: $item ? ItemData::from($item) : null,
            entity_fields: $entityFields ? CustomFieldData::collection($entityFields) : null,
            income_accounts_list: $incomeAccounts ? AccountData::collection($incomeAccounts) : null,
            purchase_accounts_list: $purchaseAccounts ? AccountData::collection($purchaseAccounts) : null,
            inventory_accounts_list: $inventoryAccounts ? AccountData::collection($inventoryAccounts) : null,
            taxes: $taxes ? TaxData::collection($taxes) : null,
            message: $response->json('message'),
        );
    }
}
