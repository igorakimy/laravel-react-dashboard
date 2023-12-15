<?php

namespace App\Data\Integration\ZohoInventory\CompositeItem;

use App\Data\Integration\ZohoInventory\Comment\CommentData;
use App\Data\Integration\ZohoInventory\Document\DocumentData;
use App\Data\Integration\ZohoInventory\PackageDetailsData;
use App\Data\Integration\ZohoInventory\Setting\CustomFieldData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

final class CompositeItemData extends Data
{
    public function __construct(
        public string|Optional $account_id,
        public string|Optional $account_name,
        public float|Optional $actual_available_for_sale_stock,
        public string|Optional $actual_available_for_sale_stock_formatted,
        public float|Optional $actual_available_stock,
        public string|Optional $actual_available_stock_formatted,
        public float|Optional $actual_committed_stock,
        public string|Optional $actual_committed_stock_formatted,
        public string|Optional $asset_value,
        public string|Optional $asset_value_formatted,
        public float|Optional $available_for_sale_stock,
        public string|Optional $available_for_sale_stock_formatted,
        public float|Optional $available_stock,
        public string|Optional $available_stock_formatted,
        public string|Optional $brand,
        public string|Optional $category_id,
        public string|Optional $category_name,

        #[DataCollectionOf(CommentData::class)]
        public DataCollection|Optional $comments,

        public float|Optional $committed_stock,
        public string|Optional $committed_stock_formatted,

        #[DataCollectionOf(CompositeComponentItemData::class)]
        public DataCollection|Optional $composite_component_items,

        public string $composite_item_id,

        #[DataCollectionOf(CustomFieldData::class)]
        public DataCollection|Optional $custom_fields,

        public string|null $description,

        #[DataCollectionOf(DocumentData::class)]
        public DataCollection|Optional $documents,

        public string|Optional $ean,
        public string|Optional $ean_formatted,
        public string|Optional $image_document_id,
        public string|Optional $image_name,
        public float|string|Optional $initial_stock,
        public string|Optional $initial_stock_formatted,
        public float|string|Optional $initial_stock_rate,
        public string|Optional $inventory_account_id,
        public string|Optional $inventory_account_name,
        public bool|Optional $is_boxing_exist,
        public bool $is_combo_product,
        public bool $is_returnable,
        public bool $is_taxable,
        public string|Optional $isbn,
        public string $item_type,
        public string|Optional $item_type_formatted,
        public float|Optional $label_rate,
        public string|Optional $label_rate_formatted,
        public string $last_modified_time,
        public string|Optional $manufacturer,

        #[DataCollectionOf(CompositeComponentItemData::class)]
        public DataCollection|Optional $mapped_items,

        public string $name,
        public string|Optional $offline_created_date_with_time,
        public string|Optional $offline_created_date_with_time_formatted,
        public PackageDetailsData|Optional $package_details,
        public string|Optional $page_layout_id,
        public string|Optional $part_number,
        public string|Optional $pricebook_discount,
        public float|Optional $pricebook_rate,
        public string|Optional $product_description,
        public string|Optional $product_short_description,
        public string|Optional $purchase_account_id,
        public string|Optional $purchase_account_name,
        public string|Optional $purchase_description,
        public float $purchase_rate,
        public string|Optional $purchase_rate_formatted,
        public float $rate,
        public string|Optional $rate_formatted,
        public int|Optional $reorder_level,
        public string|Optional $reorder_level_formatted,
        public float|Optional $sales_rate,
        public string|Optional $sales_rate_formatted,
        public string|Optional $seo_description,
        public string|Optional $seo_keyword,
        public string|Optional $seo_title,
        public bool|Optional $show_in_storefront,
        public string $sku,
        public string|Optional $source,
        public string|Optional $source_formatted,
        public string|Optional $specificationset_id,
        public string|Optional $specificationset_name,
        public string $status,
        public string|Optional $status_formatted,
        public float|Optional $stock_on_hand,
        public string|Optional $stock_on_hand_formatted,
        public string|Optional $tax_exemption_code,
        public string|Optional $tax_exemption_id,
        public string|Optional $tax_id,
        public string|Optional $tax_name,
        public string|Optional $tax_name_formatted,
        public float|Optional $tax_percentage,
        public string|Optional $tax_type,
        public string|Optional $unit,
        public string|Optional $upc,
        public string|Optional $upc_formatted,
        public string|Optional $url,
        public string|Optional $vendor_id,
        public string|Optional $vendor_name,
        public string|Optional $assembly_type,
        public string|Optional $created_time,
        public bool|Optional $track_serial_number,
    ) {
    }
}
