<?php

namespace App\Data\Integration\ZohoInventory\CompositeItem;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class CompositeComponentItemData extends Data
{
    public function __construct(
        public string|Optional $actual_available_stock,
        public string|Optional $actual_available_stock_formatted,
        public string|Optional $available_stock,
        public string|Optional $available_stock_formatted,
        public string|Optional $description,
        public string|Optional $image_document_id,
        public string|Optional $image_name,
        public string|Optional $image_type,
        public string|Optional $inventory_account_id,
        public bool $is_combo_product,
        public string $item_id,
        public int $item_order,
        public string $mapped_item_id,
        public string $name,
        public string $product_type,
        public string|Optional $purchase_account_id,
        public string|Optional $purchase_description,
        public float|Optional $purchase_rate,
        public string|Optional $purchase_rate_formatted,
        public float $quantity,
        public float $rate,
        public string $rate_formatted,
        public string $sku,
        public int $status,
        public string|Optional $stock_on_hand,
        public string|Optional $stock_on_hand_formatted,
        public string $unit,
    ) {
    }
}
