<?php

namespace App\Data\Integration\ZohoBooks\Item;

use App\Data\Integration\ZohoBooks\CustomField\CustomFieldData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

final class ItemUpdateData extends Data
{
    public function __construct(
        public string $name,
        public float $rate,
        public string $description,
        public string|null|Optional $tax_id,
        public string|null|Optional $tax_percentage,
        public string $sku,

        // goods / service / digital_service
        public string $product_type,

        public bool $is_taxable,
        public string|Optional $tax_exemption_id,
        public string $account_id,

        // sales / purchases / sales_and_purchases / inventory
        // public string $item_type,

        public string $purchase_description,
        public string $purchase_rate,
        public string $purchase_account_id,
        public string|Optional $inventory_account_id,
        public string $vendor_id,
        public string $reorder_level,
        public string $initial_stock,
        public string $initial_stock_rate,

        #[DataCollectionOf(CustomFieldData::class)]
        public DataCollection $custom_fields,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            name: $request->input('name'),
            rate: $request->input('rate'),
            description: $request->input('description', ''),
            tax_id: $request->input('tax_id'),
            tax_percentage: $request->input('tax_percentage', ''),
            sku: $request->input('sku'),
            product_type: $request->input('product_type', 'goods'),
            is_taxable: $request->input('is_taxable', true),
            tax_exemption_id: $request->input('tax_exemption_id', ''),
            account_id: $request->input('account_id', ''),
            // item_type: $request->input('item_type', 'sales'),
            purchase_description: $request->input('purchase_description', ''),
            purchase_rate: $request->input('purchase_rate', ''),
            purchase_account_id: $request->input('purchase_account_id', ''),
            inventory_account_id: $request->input('inventory_account_id', Optional::create()),
            vendor_id: $request->input('vendor_id', ''),
            reorder_level: $request->input('reorder_level', ''),
            initial_stock: $request->input('initial_stock', ''),
            initial_stock_rate: $request->input('initial_stock_rate', ''),
            custom_fields: CustomFieldData::collection($request->input('custom_fields', []))
        );
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'rate' => ['required', 'numeric'],
            'sku' => ['required', 'string'],
            'product_type' => [Rule::in([
                'goods',
                'service',
                'digital_service',
            ]), 'nullable'],
//            'item_type' => [Rule::in([
//                'sales',
//                'purchases',
//                'sales_and_purchases',
//                'inventory',
//            ])]
        ];
    }
}
