<?php

namespace App\Data\Integration\ZohoInventory\Tax;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class TaxData extends Data
{
    public function __construct(
        public string|Optional $country_code,
        public string|Optional $country_code_formatted,
        public string $created_time,
        public bool $deleted,
        public string|Optional $end_date,
        public string|Optional $end_date_formatted,
        public string|Optional $input_tax_id,
        public bool $is_editable,
        public bool $is_non_advol_tax,
        public bool $is_value_added,
        public string $output_tax_id,
        public string|Optional $start_date,
        public string|Optional $start_date_formatted,
        public string $tax_authority_id,
        public string $tax_id,
        public string $tax_name,
        public float $tax_percentage,
        public string $tax_percentage_formatted,
        public string $tax_specific_type,
        public string $tax_specific_type_formatted,
        public string $tax_type,
        public string $tax_type_formatted,
    ) {
    }
}
