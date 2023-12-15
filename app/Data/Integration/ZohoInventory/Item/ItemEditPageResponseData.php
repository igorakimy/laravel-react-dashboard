<?php

namespace App\Data\Integration\ZohoInventory\Item;

use App\Data\Integration\ZohoInventory\Account\AccountData;
use App\Data\Integration\ZohoInventory\Setting\CustomFieldData;
use App\Data\Integration\ZohoInventory\Tax\TaxData;
use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

final class ItemEditPageResponseData extends Data
{
    public function __construct(
        public bool $success,

        public ItemData|null $item,

        #[DataCollectionOf(CustomFieldData::class)]
        public DataCollection|null|Optional $entity_fields,

        #[DataCollectionOf(AccountData::class)]
        public DataCollection|null|Optional $income_accounts_list,

        #[DataCollectionOf(AccountData::class)]
        public DataCollection|null|Optional $purchase_accounts_list,

        #[DataCollectionOf(AccountData::class)]
        public DataCollection|null|Optional $inventory_accounts_list,

        #[DataCollectionOf(TaxData::class)]
        public DataCollection|null|Optional $taxes,

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
            item: $item ? ItemData::from($item) : ItemData::optional(),
            entity_fields: $entityFields ? CustomFieldData::collection($entityFields) : CustomFieldData::optional(),
            income_accounts_list: $incomeAccounts ? AccountData::collection($incomeAccounts) : AccountData::optional(),
            purchase_accounts_list: $purchaseAccounts ? AccountData::collection($purchaseAccounts) : AccountData::optional(),
            inventory_accounts_list: $inventoryAccounts ? AccountData::collection($inventoryAccounts) : AccountData::optional(),
            taxes: $taxes ? TaxData::collection($taxes) : TaxData::optional(),
            message: $response->json('message'),
        );
    }
}
