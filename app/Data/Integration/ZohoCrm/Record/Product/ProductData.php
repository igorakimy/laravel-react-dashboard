<?php

namespace App\Data\Integration\ZohoCrm\Record\Product;

use App\Data\Integration\ZohoCrm\Tax\TaxData;
use App\Data\Integration\ZohoCrm\User\UserData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

final class ProductData extends Data
{
    public function __construct(
        public string|null|Optional $Product_Category,
        public int|Optional $Qty_in_Demand,
        public UserData|Optional $Owner,
        public string|null|Optional $Description,
        public string|null|Optional $Vendor_Name,
        public string|null|Optional $Sales_Start_Date,

        #[DataCollectionOf(TaxData::class)]
        public DataCollection|null|Optional $Tax,

        public bool|Optional $Product_Active,
        public string|null|Optional $Record_Image,
        public UserData|Optional $Modified_By,
        public string|null|Optional $Product_Code,
        public string|null|Optional $Manufacturer,
        public string|Optional $id,
        public string|null|Optional $Modified_Time,
        public string|null|Optional $Created_Time,
        public string|null|Optional $Commission_Rate,
        public string|null|Optional $Product_Name,
        public string|null|Optional $Usage_Unit,
        public int|Optional $Qty_Ordered,
        public int|Optional $Qty_in_Stock,
        public UserData|Optional $Created_By,
        public string|Optional $SKU,
        public string|null|Optional $Sales_End_Date,
        public float|Optional $Unit_Price,
        public bool|Optional $Taxable,
        public int|Optional $Reorder_Level,
    ) {
    }

    public function with(): array
    {
        return [

        ];
    }
}
