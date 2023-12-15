<?php

namespace App\Data\Integration\ZohoInventory\Item;

use App\Data\Integration\ZohoInventory\Document\DocumentData;
use App\Data\Integration\ZohoInventory\Setting\CustomFieldData;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class ItemData extends Data
{
    public function __construct(
        public string $item_id,
        public string $name,
        public string $sku,
        public string $status,
        public string $unit,
        public float $rate,
        public float $purchase_rate,
        public string $product_type,
        public string $item_type,
        public bool $is_taxable,
        public string $description,
        public string|Optional $item_name,
        public string|Optional $unit_id,
        public string|Optional $source,
        public string|Optional $tax_id,
        public string|Optional $tax_name,
        public string|Optional $tax_type,
        public float|Optional $tax_percentage,
        public string|Optional $purchase_account_id,
        public string|Optional $purchase_account_name,
        public string|Optional $account_id,
        public string|Optional $account_name,
        public string|Optional $purchase_description,
        public string|Optional $tax_exemption_id,
        public string|Optional $tax_exemption_code,
        public float|Optional $stock_on_hand,
        public bool|Optional $has_attachment,
        public float|Optional $available_stock,
        public float|Optional $actual_available_stock,
        public string|Optional $reorder_level,
        public string|Optional $image_name,
        public string|Optional $image_type,
        public string|Optional $image_document_id,
        public string|Optional $brand,
        public string|Optional $manufacturer,

        public float|Optional $pricebook_rate,
        public string|Optional $pricing_scheme,

        #[DataCollectionOf(PriceBracketData::class)]
        public DataCollection|Optional $price_brackets,

        #[DataCollectionOf(PriceBracketData::class)]
        public DataCollection|Optional $default_price_brackets,

        public float|Optional $sales_rate,

        public string|Optional $inventory_account_id,
        public string|Optional $inventory_account_name,

        public float|string|Optional $initial_stock,
        public float|string|Optional $initial_stock_rate,
        public string|Optional $vendor_id,
        public string|Optional $vendor_name,
        public float|Optional $committed_stock,
        public float|Optional $available_for_sale_stock,

        #[DataCollectionOf(CustomFieldData::class)]
        public DataCollection|Optional $custom_fields,

        #[DataCollectionOf(DocumentData::class)]
        public DataCollection|Optional $documents,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:sO')]
        public Carbon|Optional $created_time,

        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:sO')]
        public Carbon|Optional $last_modified_time,
    ) {
    }
}
